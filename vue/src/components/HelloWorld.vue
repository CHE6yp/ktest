<template>

  <br>
  <br>
  <span>Всего позиций - {{totalItems}}</span>
  <br>
  <br>

  <div v-if="pagination" class="pagination">
    <button v-if="pagination['hydra:first']" @click="getData(pagination['hydra:first'])">First </button>
    <button v-if="pagination['hydra:previous']" @click="getData(pagination['hydra:previous'])">Previous </button>
    <span> Страница {{pagination.currentPage}} </span>
    <button v-if="pagination['hydra:next']" @click="getData(pagination['hydra:next'])">Next </button>
    <button v-if="pagination['hydra:last']" @click="getData(pagination['hydra:last'])">Last </button>
  </div>  
  <div class="hello">

    <table>
      <tr>
        <th >
          <span @click="sort('id')">id {{sorting.id? ' ('+sorting.id+')':''}}</span></th>
        <th >
          <span @click="sort('name')">name {{sorting.name? ' ('+sorting.name+')':''}}</span> <br>
          <input v-model="searchFilters.name" type="text" @input="search">
        </th>
        <th >
          <span @click="sort('category')">category {{sorting.category? ' ('+sorting.category+')':''}}</span> <br>
          <input v-model="searchFilters.category" type="text" @input="search">
        </th>
        <th >
          <span @click="sort('description')">description {{sorting.description? ' ('+sorting.description+')':''}}</span> <br>
          <input v-model="searchFilters.description" type="text" @input="search">
        </th>
        <th >
          <span @click="sort('weight')">weight {{sorting.weight? ' ('+sorting.weight+')':''}}</span> <br>
          <input v-model="searchFilters.weight" type="text" @input="search">
        </th>
      </tr>
      <tr v-for="item in items" :key="item.id">
        <td>{{item.id}}</td>
        <td>{{item.name}}</td>
        <td>{{item.category}}</td>
        <td>{{item.description}}</td>
        <td>{{item.weight}}</td>
      </tr>
    </table>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'HelloWorld',
  props: {
    msg: String
  },
  mounted () {
    this.getData('/api/products?page=1');
  },
  data () {
    return {
      file: null,
      response: null,
      items: null,
      sorting: {
        id: null,
        name: null,
        category: null,
        description: null,
        weight: null,
      },
      searchFilters: {
        name: "",
        category: "",
        description: "",
        weight: "",
      },
      pagination: null,
      totalItems: 0,
    };
  },
  methods: {
    search() {
      this.buildQuery();
    },
    sort(param) {
      for (const sortParam in this.sorting) {
        if (param == sortParam) {
          if (this.sorting[sortParam] == null) {
            this.sorting[sortParam] = 'asc';
          } else if (this.sorting[sortParam] == 'asc') {
            this.sorting[sortParam] = 'desc';
          } else {
            this.sorting[sortParam] = null;
          }
        }
      }
      
      this.buildQuery();
    },
    buildQuery() {
      let queryArr = [];
      for (const sortParam in this.sorting) {
        if (this.sorting[sortParam]) queryArr.push("order["+sortParam+"]=" + this.sorting[sortParam]);  
      }
      for (const filter in this.searchFilters) {
        if (this.searchFilters[filter]!="") queryArr.push(filter+'='+this.searchFilters[filter]);
      }

      let query = queryArr.join("&");

      this.getData('/api/products?'+ query);
    },
    async getData(query) {
      await axios
        .get('http://localhost:8080'+query)
        .then( (response) => {
          this.response = response
          this.items = this.response.data['hydra:member'];
          this.pagination = this.response.data['hydra:view'];
          this.pagination.currentPage = this.pagination['@id'].split('page=')[1];
          this.totalItems = this.response.data['hydra:totalItems'];
        });
    }
  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
tr:nth-child(even) {
  background-color: #cfc;
}
</style>
