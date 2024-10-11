<template>
    <!-- ENCABEZADO -->
    <div class="prose max-w-none">
        <div class="grid grid-cols-1">
            <h1 class="text-center p-4 m-0">Producción</h1>
        </div>
    </div>
    <div class="overflow-x-auto">
        <!-- Tabla de productos y stock -->
        <table class="table">
            <thead>
                <tr>
                    <th>Productos</th>
                    <th>Stock semana anterior</th>
                    <th v-for="(day, index) in daysOfWeek" :key="index">{{ day }}</th>
                    <th>Total proyectado</th>
                    <th>Total entregado</th>
                    <th>Total hasta hoy</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(producto, rowIndex) in productos" :key="rowIndex">
                    <td>{{ producto.descripcion }}</td>
                    <td>{{ producto.stock_semana_anterior }}</td>
                    <td v-for="(day, dayIndex) in daysOfWeek" :key="dayIndex">
                        <input v-model.number="producto.stock[dayIndex]" @input="updateStock(rowIndex, dayIndex)"
                            type="number" placeholder="Ingresa el Stock"
                            class="input input-bordered w-full max-w-xs mb-2" />
                    </td>
                    <td>{{ calculateTotalProjected(producto.stock, producto.stock_semana_anterior) }}</td>
                    <td>{{ stockData.find(item => item.idproducto === producto.id).entregados }}</td>
                    <td>{{ calculateTotalUntilToday(producto.stock, producto.stock_semana_anterior, producto.id) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="flex justify-end p-6">
            <button class="btn btn-outline btn-success" @click="putStock">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 8.25H7.5a2.25 2.25 0 0 0-2.25 2.25v9a2.25 2.25 0 0 0 2.25 2.25h9a2.25 2.25 0 0 0 2.25-2.25v-9a2.25 2.25 0 0 0-2.25-2.25H15M9 12l3 3m0 0 3-3m-3 3V2.25" />
                </svg>
                Actualizar stock</button>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            daysOfWeek: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
            weeklyStock: [], // Almacena el stock de la semana actual
            productos: [],
            stockData: [], // Aquí guardamos los datos de la API
            lastWeek: [],
        };
    },
    async mounted() {
        this.getStock()
    },
    methods: {
        updateStock(rowIndex, dayIndex) {
            const currentStock = this.productos[rowIndex].stock[dayIndex];
            // Actualiza los días posteriores con el valor actual
            for (let i = dayIndex + 1; i < this.daysOfWeek.length; i++) {
                this.productos[rowIndex].stock[i] = currentStock;
            }
        },
        calculateTotal(stock, stock_semana_anterior) {
            // Aquí puedes realizar los cálculos que necesites con ambos valores
            // Por ejemplo:
            return stock.reduce((total, num) => total + num, 0) + parseInt(stock_semana_anterior);
        },
        //PUT todo el stock
        async putStock() {
            // Obtener la fecha actual
            let today = new Date();

            // Calcular el número de días a restar para llegar al lunes
            let dayOfWeek = today.getDay(); // 0 es Domingo, 1 es Lunes, ..., 6 es Sábado
            let daysToMonday = (dayOfWeek === 0 ? 6 : dayOfWeek - 1); // Si es domingo, restar 6, si no, restar dayOfWeek - 1

            // Restar los días necesarios para obtener la fecha del lunes
            let monday = new Date(today.setDate(today.getDate() - daysToMonday));

            for (let i = 0; i < this.productos.length; i++) {
                let idp = this.productos[i].id;
                let array = this.productos[i].stock;

                // Crear una copia de la fecha del lunes
                let currentDay = new Date(monday);

                for (let j = 0; j < array.length; j++) {
                    let stock = array[j];

                    // Formatear la fecha actual al formato YYYY-MM-DD
                    let dia = currentDay.toISOString().split('T')[0];
                    console.log(dia)

                    let url = `https://nuestrocampo.cl/api/stock/update.php?idp=${idp}&fecha=${dia}&stock=${stock}`;
                    console.log(stock);

                    axios.put(url).then(function (response) {
                        console.log(response.data);
                    })

                    // Sumar 1 día a la fecha
                    currentDay.setDate(currentDay.getDate() + 1);
                }
                console.log(this.productos[i].stock);
            }

            location.reload()
        },
        async getProductos() {
            const url = 'https://nuestrocampo.cl/api/productos/read.php';

            await axios
                .get(url)
                .then(response => {
                    this.productos = response.data.map(producto => {
                        const stockData = this.stockData.find(item => item.idproducto === producto.id);
                        return {
                            ...producto,
                            stock: stockData ? JSON.parse(stockData.stock) : [0, 0, 0, 0, 0, 0, 0]
                        };
                    });
                })
                .catch(error => {
                    console.error('Error al obtener los productos: ', error);
                });
        },
        //GET el stock (si no hay stock, lo crea)
        async getStock() {
            try {
                // Llamar a la API para obtener el stock de la semana actual
                const response = await axios.get('https://nuestrocampo.cl/api/stock/read.php');
                this.stockData = response.data; // Asignar el stock a stockData

                await this.getProductos()
                await this.getLastWeek()
            } catch (error) {
                await this.postStock()
                console.error('Error al obtener el stock de la semana actual:', error);
            }
        },
        //POST crea la base del stock
        async postStock() {
            console.log('POST STOCK')
            await this.getProductos()

            // Obtener la fecha actual
            let today = new Date();

            // Calcular el número de días a restar para llegar al lunes
            let dayOfWeek = today.getDay(); // 0 es Domingo, 1 es Lunes, ..., 6 es Sábado
            let daysToMonday = (dayOfWeek === 0 ? 6 : dayOfWeek - 1); // Si es domingo, restar 6, si no, restar dayOfWeek - 1

            // Restar los días necesarios para obtener la fecha del lunes
            let monday = new Date(today.setDate(today.getDate() - daysToMonday));

            for (let i = 0; i < this.productos.length; i++) {
                let idp = this.productos[i].id;
                let array = this.productos[i].stock;

                // Crear una copia de la fecha del lunes
                let currentDay = new Date(monday);

                for (let j = 0; j < array.length; j++) {
                    let stock = array[j];

                    // Formatear la fecha actual al formato YYYY-MM-DD
                    let dia = currentDay.toISOString().split('T')[0];
                    console.log(dia)

                    let url = `https://nuestrocampo.cl/api/stock/create.php?idp=${idp}&fecha=${dia}&stock=${stock}`;
                    await axios.post(url).then(function (response) {
                        console.log(response);
                    })
                    console.log(stock);

                    // Sumar 1 día a la fecha
                    currentDay.setDate(currentDay.getDate() + 1);
                }
                console.log(this.productos[i].stock);
            }
        },
        //GET stock de la semana anterior
        async getLastWeek() {
            try {
                let url = `https://nuestrocampo.cl/api/stock/read-last-week.php`;
                const response = await axios.get(url);
                this.lastWeek = response.data;

                // Fusionar la información de la semana anterior con la información actual de stock
                this.productos.forEach((item, index) => {
                    const lastWeekItem = this.lastWeek.find((lastWeekItem) => lastWeekItem.idproducto === item.id);
                    item.stock_semana_anterior = lastWeekItem ? lastWeekItem.stock_semana_anterior : 0; // Valor por defecto
                });
            } catch (error) {
                if (error.response && error.response.status === 404) {
                    console.log("Stock de la semana anterior no encontrado (404), omitiendo...");

                    // Si ocurre un error 404, rellenar stock_semana_anterior con 0
                    this.productos.forEach(item => {
                        item.stock_semana_anterior = 0; // Valor predeterminado 0 si hay un error
                    });
                } else {
                    // Maneja otros errores aquí si es necesario
                    console.error('Error al obtener el stock de la semana anterior:', error);
                }
            }
        },

        calculateTotalProjected(stock, stock_semana_anterior) {
            const totalStock = stock.reduce((total, num) => total + num, 0);
            return totalStock + parseInt(stock_semana_anterior);
        },

        calculateTotalUntilToday(stock, stock_semana_anterior, idProducto) {
            const today = new Date().getDay(); // 0 es domingo, 1 es lunes, ..., 6 es sábado
            const adjustedToday = today === 0 ? 6 : today - 1; // Ajustamos para que 0 sea lunes y 6 domingo

            const totalStock = stock.slice(0, adjustedToday + 1).reduce((total, num) => total + num, 0);
            const stockDataItem = this.stockData.find(item => item.idproducto === idProducto);
            const entregados = stockDataItem ? parseInt(stockDataItem.entregados) || 0 : 0;

            return totalStock + parseInt(stock_semana_anterior) - entregados;
        }
    }
}
</script>
