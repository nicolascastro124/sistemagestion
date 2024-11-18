<template>
    <nav>
      <ul>
        <li v-for="item in menuItems" :key="item.id">
          <a :href="item.link">{{ item.name }}</a>
        </li>
      </ul>
    </nav>
</template>
  
<script>
  export default {
    name: 'Menu',
    data() {
      return {
        menuItems: [
          { id: 1, name: 'Inicio', link: '/' },
          { id: 2, name: 'Acerca de', link: '/about' },
          { id: 3, name: 'Servicios', link: '/services' },
          { id: 4, name: 'Contacto', link: '/contact' },
        ],
      };
    },
  };
</script>
  
<style scoped>
  nav {
    background-color: #333;
    padding: 1em;
  }
  
  ul {
    list-style-type: none;
    padding: 0;
  }
  
  li {
    display: inline;
    margin-right: 1em;
  }
  
  a {
    color: white;
    text-decoration: none;
  }
  
  a:hover {
    text-decoration: underline;
  }
</style>
  