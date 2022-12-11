<template>
  <div>
    <div class="form" v-show="!isUploading">
      <label
        :class="{'label--uploaded': this.file}"
        class="label"
      >
        

        <span>{{this.file ?  this.file.name : 'Выберите файл'}}</span>

        <input type="file" @change="uploadFile" ref="file">
      </label>
      <button class="submit" @click="submitFile">Upload!</button>
    </div>
    
    <div v-show="isUploading" id='preloader'>
      <svg class='spinner' height='50px' viewBox='0 0 66 66' width='50px' xmlns='http://www.w3.org/2000/svg'>
         <circle class='path' cx='33' cy='33' fill='none' r='30' stroke-linecap='round' stroke-width='4'/>
      </svg>
    </div>
  </div>

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
      <span class="pagination__current">{{ currentPage || 1 }}</span>
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

    <table width="100%">
      <tr>
        <th width="5%" >
          <span class="sorting" @click="sort('id')">id {{sorting.id? ' ('+sorting.id+')':''}}</span></th>
        <th width="15%">
          <span class="sorting" @click="sort('name')">name {{sorting.name? ' ('+sorting.name+')':''}}</span> <br>
          <input class="searching" v-model="searchFilters.name" type="text" @input="search">
        </th>
        <th width="15%">
          <span class="sorting" @click="sort('category')">category {{sorting.category? ' ('+sorting.category+')':''}}</span> <br>
          <input class="searching" v-model="searchFilters.category" type="text" @input="search">
        </th>
        <th width="40%">
          <span class="sorting" @click="sort('description')">description {{sorting.description? ' ('+sorting.description+')':''}}</span> <br>
          <input class="searching" v-model="searchFilters.description" type="text" @input="search">
        </th>
        <th width="15%">
          <span class="sorting" @click="sort('weight')">weight {{sorting.weight? ' ('+sorting.weight+')':''}}</span> <br>
          <input class="searching" v-model="searchFilters.weight" type="text" @input="search">
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
      <span class="pagination__current">{{ currentPage || 1 }}</span>
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
      isUploading: false,
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
    uploadFile() {
      this.file = this.$refs.file.files[0];
    },
    submitFile() {
      this.isUploading = true
      const formData = new FormData();
      formData.append('file', this.file);
      const headers = { 'Content-Type': 'multipart/form-data' };
      axios.post('http://127.0.0.1:8080/api/media_objects', formData, { headers })
        .then((res) => {
          res.data.files; // binary representation of the file
          res.status; // HTTP status
          window.location.href = "http://127.0.0.1:8080"+res.data['csvContentUrl'];
          this.buildQuery();
        })
        .finally(() => {
          this.isUploading = false
        });
    },
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
        .get('http://127.0.0.1:8080'+query)
        .then( (response) => {
          this.response = response
          this.items = this.response.data['hydra:member'];
          this.pagination = this.response.data['hydra:view'];
          this.currentPage = this.pagination ? this.pagination['@id'].split('page=')[1]:1;
          // eslint-disable-next-line
          debugger;
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
th {
  vertical-align: top;
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
  opacity: .2;
}

.sorting {
  cursor: pointer;
}
.searching {
  width: 100%;
}

.form {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100px;
}

.label {
  width: 360px;
  height: 56px;
  background: #eee;
  padding: 0 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  border: none;
  outline: none;
  cursor: pointer;
}

.label--uploaded {
  background-color: #cfc;
}

.label input {
  visibility: hidden;
  width: 0;
}

.submit {
  margin-left: 16px;
  height: 56px;
  padding: 0 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 4px;
  border: none;
  outline: none;
  background: #edecea;
}

#preloader{position:relative;height: 100px;overflow:hidden;background:#fff;left:0;right:0;top:0;bottom:0;;z-index:9999}
.spinner{position:absolute;top:calc(50% - 20px);left:calc(50% - 20px);animation:rotator 1.4s linear infinite}
@keyframes rotator{0%{transform:rotate(0deg)}100%{transform:rotate(270deg)}}
.path{stroke-dasharray:187;stroke-dashoffset:0;transform-origin:center;animation:dash 1.4s ease-in-out infinite,colors 5.6s ease-in-out infinite}
@keyframes colors{0%{stroke:#4285F4}25%{stroke:#DE3E35}50%{stroke:#F7C223}75%{stroke:#1B9A59}100%{stroke:#4285F4}}
@keyframes dash{0%{stroke-dashoffset:187}50%{stroke-dashoffset:46.75;transform:rotate(135deg)}100%{stroke-dashoffset:187;transform:rotate(450deg)}}
</style>
