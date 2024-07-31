<template>
    <div>
      <div class="dropdown" :class="{ open: isDropdownOpen }">
        <label tabindex="0" class="btn btn-primary">Menu</label>
        <ul tabindex="0" class="dropdown-content menu p-2 shadow bg-base-100 rounded-box w-52">
          <li><router-link to="/route1" @click.native="close"><a>Route 1</a></router-link></li>
          <li><router-link to="/route2" @click.native="closeDropdown">Route 2</router-link></li>
          <li><router-link to="/route3" @click.native="closeDropdown">Route 3</router-link></li>
        </ul>
      </div>
    </div>
  </template>
  
  <script>
  export default {
    data() {
      return {
        isDropdownOpen: false,
      };
    },
    methods: {
      toggleDropdown() {
        this.isDropdownOpen = !this.isDropdownOpen;
      },
      closeDropdown() {
        this.isDropdownOpen = false;
      },
      close() {
        document.activeElement.blur();
      }
    },
    watch: {
      $route() {
        this.closeDropdown();
      },
    },
  };
  </script>
  
  <style>
  .dropdown.open [tabindex="0"] {
    display: block;
  }
  </style>
  