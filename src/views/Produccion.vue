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
                    <th v-for="(day, index) in daysOfWeek" :key="index">{{ day }}</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(producto, rowIndex) in productos" :key="rowIndex">
                    <td>{{ producto.descripcion }}</td>
                    <td v-for="(day, dayIndex) in daysOfWeek" :key="dayIndex">
                        <input v-model.number="producto.stock[dayIndex]" @input="updateStock(rowIndex, dayIndex)"
                            type="number" placeholder="Ingresa el Stock" class="input input-bordered w-full max-w-xs mb-2"/>
                    </td>
                    <td>{{ calculateTotal(producto.stock) }}</td>
                </tr>
            </tbody>
        </table>

        <button class="btn" @click="putStock">Guardar datos</button>
        <button class="btn" @click="postStock">post Stock</button>
        <button class="btn" @click="getStock">Cargar stock de la semana actual</button>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            daysOfWeek: ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
            weeklyStock: [], // Almacena el stock de la semana actual
            productos: [],
            stockData: [] // Aquí guardamos los datos de la API
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
        calculateTotal(stockArray) {
            return stockArray.reduce((total, num) => total + num, 0); // Suma el stock de cada día
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
                            stock: stockData ? JSON.parse(stockData.stock) : [0, 0, 0, 0, 0]
                        };
                    });
                    console.log('productos obtenidos')
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
                console.log('Stock de la semana actual:', this.stockData);
                await this.getProductos()
            } catch (error) {
                this.postStock()
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
    }
}
</script>
