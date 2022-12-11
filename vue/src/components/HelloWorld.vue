<template>
  <br>
  <br>
  <span>Всего позиций - {{totalItems}}</span>
  <br>
  <br>
  <ul v-if="pagination" class="pagination">
    <li class="pagination__item">
      <button :disabled="!pagination['hydra:first']" @click="getData(pagination['hydra:first'])" class="pagination__button pagination__button--prev">
        <svg width=50px height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><path d="M256 0C114.615 0 0 114.615 0 256s114.615 256 256 256 256-114.615 256-256S397.385 0 256 0zm24.875 269.313-96 64A16.02 16.02 0 0 1 176 336a15.991 15.991 0 0 1-16-16V192a16.01 16.01 0 0 1 8.449-14.109c5.203-2.773 11.516-2.484 16.426.797l96 64c4.453 2.968 7.125 7.96 7.125 13.312s-2.672 10.344-7.125 13.313zM368 320c0 8.836-7.164 16-16 16h-16c-8.836 0-16-7.164-16-16V192c0-8.836 7.164-16 16-16h16c8.836 0 16 7.164 16 16v128z"/></svg>
      </button>
    </li>
    
    <li class="pagination__item">
      <button :disabled="!pagination['hydra:previous']" @click="getData(pagination['hydra:previous'])" class="pagination__button pagination__button--prev">
        <svg width=50px height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300" style="enable-background:new 0 0 300 300" xml:space="preserve"><path d="M150 0C67.157 0 0 67.157 0 150c0 82.841 67.157 150 150 150s150-67.159 150-150C300 67.157 232.843 0 150 0zm45.708 160.159a14.146 14.146 0 0 1-2.368 1.886l-56.295 56.295c-2.734 2.736-6.318 4.103-9.902 4.103s-7.166-1.367-9.902-4.103c-5.47-5.47-5.47-14.34 0-19.808l48.509-48.516-48.265-48.265c-5.47-5.473-5.47-14.34 0-19.808 5.47-5.47 14.338-5.467 19.808-.003l56.046 56.043a13.956 13.956 0 0 1 2.365 1.886c2.796 2.796 4.145 6.479 4.082 10.146.066 3.665-1.28 7.346-4.078 10.144z"/></svg>
      </button>
    </li>
    
    <li class="pagination__item">
      <span class="pagination__current">{{ pagination.currentPage }}</span>
    </li>

    <li class="pagination__item">
      <button :disabled="!pagination['hydra:next']" @click="getData(pagination['hydra:next'])" class="pagination__button">
        <svg width=50px height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300" style="enable-background:new 0 0 300 300" xml:space="preserve"><path d="M150 0C67.157 0 0 67.157 0 150c0 82.841 67.157 150 150 150s150-67.159 150-150C300 67.157 232.843 0 150 0zm45.708 160.159a14.146 14.146 0 0 1-2.368 1.886l-56.295 56.295c-2.734 2.736-6.318 4.103-9.902 4.103s-7.166-1.367-9.902-4.103c-5.47-5.47-5.47-14.34 0-19.808l48.509-48.516-48.265-48.265c-5.47-5.473-5.47-14.34 0-19.808 5.47-5.47 14.338-5.467 19.808-.003l56.046 56.043a13.956 13.956 0 0 1 2.365 1.886c2.796 2.796 4.145 6.479 4.082 10.146.066 3.665-1.28 7.346-4.078 10.144z"/></svg>
      </button>
    </li>
    
    <li class="pagination__item">
      <button :disabled="!pagination['hydra:last']" @click="getData(pagination['hydra:last'])" class="pagination__button">
        <svg width=50px height="50px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><path d="M256 0C114.615 0 0 114.615 0 256s114.615 256 256 256 256-114.615 256-256S397.385 0 256 0zm24.875 269.313-96 64A16.02 16.02 0 0 1 176 336a15.991 15.991 0 0 1-16-16V192a16.01 16.01 0 0 1 8.449-14.109c5.203-2.773 11.516-2.484 16.426.797l96 64c4.453 2.968 7.125 7.96 7.125 13.312s-2.672 10.344-7.125 13.313zM368 320c0 8.836-7.164 16-16 16h-16c-8.836 0-16-7.164-16-16V192c0-8.836 7.164-16 16-16h16c8.836 0 16 7.164 16 16v128z"/></svg>
      </button>
    </li>
  </ul>
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

.pagination {
  display: flex;
  justify-content: center;
  grid-gap: 10px;
  list-style: none;
}

.pagination__button {
  display: flex;
  margin: 0;
  padding: 0;
  border: none;
  background: none;
  cursor: pointer;
}

.pagination__button--prev {
  transform: rotate(180deg);
}

.pagination__current {
  height: 100%;
  display: flex;
  align-items: center;
  padding: 0 16px;
  font-size: 25px;
  font-weight: 600;
  font-feature-settings: "tnum";
  font-variant-numeric: tabular-nums;
}

.pagination__button svg {
  fill: #353535;
  transition: all 300ms;
}

.pagination__button:hover svg {
  fill: #000;
}

.pagination__button[disabled] {
  cursor: not-allowed;
  opacity: .4;
}
</style>
