<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de cotizaciones
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-8 py-8">
                    <button @click="openModal()"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Crear nueva
                        cotización</button>

                    <div class="flex justify-start">
                        <div class="mb-3 xl:w-50">
                            <input type="search"
                                class="relative m-0 block w-full min-w-0 flex-auto rounded border border-solid border-neutral-300 bg-transparent bg-clip-padding px-3 py-1.5 text-base font-normal text-neutral-700 outline-none transition duration-300 ease-in-out focus:border-primary-600 focus:text-neutral-700 focus:shadow-te-primary focus:outline-none dark:border-neutral-600 dark:text-neutral-200 dark:placeholder:text-neutral-200"
                                id="search" v-model="search" placeholder="Buscar..." />
                        </div>
                    </div>
                    <div class="mb-4">
                        <select v-model="order" id="order" name="order" class="
                                        text-[15px]
                                        cursor-pointer
                                        border-0 border-b-[1px]
                                        focus:ring-0
                                        pl-2
                                        pb-1">
                            <option value="id">Orden</option>
                            <option value="date">Fecha</option>
                            <option value="description">Descripción</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="filter" class="block text-gray-700 text-sm font-bold mb-2">Filtrado por fecha:</label>
                        <input id="filter" name="filter" v-model="filter" type="date" class="
                                bg-transparent
                                w-25
                                border-0 border-b-[1px]
                                font-light
                                text-tiny
                                mb-5
                                py-2
                                placeholder:font-light placeholder:text-tiny
                                focus:ring-0 focus:border-spring-green" />
                    </div>
                    <table class="table-reponsive w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Fecha</th>
                                <th class="px-4 py-2">Descripción</th>
                                <th class="px-4 py-2">Cliente</th>
                                <th class="px-4 py-2">Servicios</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in quotes">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.date }}</td>
                                <td class="border px-4 py-2">{{ row.description }}</td>
                                <td class="border px-4 py-2">{{ row.user.first_name }} {{ row.user.last_name }}</td>
                                <td class="border px-4 py-2">
                                    <ul>
                                        <li v-for="service in row.services">
                                            - {{ service.description }}
                                        </li>
                                    </ul>
                                </td>
                                <td class="border px-4 py-2">
                                    <button @click="edit(row)"
                                        class="bg-yellow-500 hover:bg-yellow-700 mx-2 text-white font-bold py-2 px-4 rounded">Editar</button>
                                    <button @click="show(row)"
                                        class="bg-orange-500 hover:bg-orange-700 mx-2 text-white font-bold py-2 px-4 rounded">Ver</button>
                                    <button @click="destroy(row)"
                                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">Eliminar</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="fixed z-10 inset-0 overflow-y-auto ease-out duration-400" v-if="isOpen">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">

                            <div class="fixed inset-0 transition-opacity">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <!-- This element is to trick the browser into centering the modal contents. -->
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>​
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
                                role="dialog" aria-modal="true" aria-labelledby="modal-headline">
                                <form>
                                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                        <div class="">
                                            <div class="mb-4">
                                                <label for="description"
                                                    class="block text-gray-700 text-sm font-bold mb-2">Descripción:</label>
                                                <textarea name="description" id="description" v-model="form.description" :disabled="disabled == true"
                                                    cols="5" rows="5" 
                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                    placeholder="Ingrese descripción"></textarea>

                                                <ShowErrors v-if="validations.description" :errors="validations.description">
                                                </ShowErrors>
                                            </div>
                                            <div class="mb-4">
                                                <select v-model="form.user_id" id="user_id"  name="user_id" :disabled="disabled == true" class="
                                                                    text-[15px]
                                                                    cursor-pointer
                                                                    border-0 border-b-[1px]
                                                                    focus:ring-0
                                                                    pl-2
                                                                    pb-1">
                                                    <option v-for="lead in leads" :value="lead.id" :key="lead.id" >
                                                        {{ lead.first_name }} {{ lead.last_name }}
                                                    </option>
                                                </select>
                                            </div>
                                            <div v-if="!editMode && !disabled" class="mb-4">
                                                <Multiselect v-model="value" mode="tags" placeholder="Seleccione servicios"
                                                    :object="true" :resolve-on-load="false" :delay="0" :min-chars="1"
                                                    :options="services" trackBy="value" label="label"
                                                    class="style-multiselect" :close-on-select="true"
                                                    @click="setSelected(value)">
                                                    <template v-slot:multiplelabel>
                                                        <div class="multiselect-multiple-label ">
                                                            {{ value }}
                                                        </div>
                                                    </template>
                                                </Multiselect>
                                            </div>
                                            <div v-if="disabled">
                                            </div>
                                            <div class="mb-4">
                                                <input id="date" name="date" v-model="form.date" :disabled="disabled == true" type="date" class="
                                                            bg-transparent
                                                            w-full
                                                            border-0 border-b-[1px]
                                                            font-light
                                                            text-tiny
                                                            mb-5
                                                            py-2
                                                            placeholder:font-light placeholder:text-tiny
                                                            focus:ring-0 focus:border-spring-green" />
                                                <ShowErrors v-if="validations.date" :errors="validations.date">
                                                </ShowErrors>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <span v-if="!disabled" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                            <button type="button"
                                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                                v-show="!editMode" @click="save(form)">
                                                Guardar
                                            </button>
                                        </span>
                                        <span v-if="!disabled" class="flex w-full rounded-md shadow-sm sm:ml-3 sm:w-auto">
                                            <button type="button"
                                                class="inline-flex justify-center w-full rounded-md border border-transparent px-4 py-2 bg-green-600 text-base leading-6 font-medium text-white shadow-sm hover:bg-green-500 focus:outline-none focus:border-green-700 focus:shadow-outline-green transition ease-in-out duration-150 sm:text-sm sm:leading-5"
                                                v-show="editMode" @click="update(form)">
                                                Actualizar
                                            </button>
                                        </span>
                                        <span class="mt-3 flex w-full rounded-md shadow-sm sm:mt-0 sm:w-auto">

                                            <button @click="closeModal()" type="button"
                                                class="inline-flex justify-center w-full rounded-md border border-gray-300 px-4 py-2 bg-white text-base leading-6 font-medium text-gray-700 shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition ease-in-out duration-150 sm:text-sm sm:leading-5">
                                                Cancelar
                                            </button>
                                        </span>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </app-layout>
</template>
<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import Welcome from '@/Pages/Welcome.vue'
import InputSearch from '@/Components/InputSearch.vue'
import ShowErrors from '@/Components/ShowErrors.vue'
import Multiselect from '@vueform/multiselect'
import { API } from "@/helper"

export default {
    components: {
        AppLayout,
        InputSearch,
        Welcome,
        ShowErrors,
        Multiselect
    },
    props: ['data', 'errors'],
    data() {
        return {
            value: null,
            options: [
                'Batman',
                'Robin',
                'Joker',
            ],
            editMode: false,
            isOpen: false,
            form: {
                date: null,
                description: null,
                services: [],
                user_id: this.quote?.user_id || null,
                date: null,
            },
            quotes: [],
            validations: [],
            services: [],
            search: '',
            skills: [],
            quote: [],
            value: null,
            leads: [],
            order: 'id',
            filter: null,
            disabled: false,
            servicesSelected: [],
        }
    },
    computed: {
        myServices() {
            let value = (this.editMode) ? this.quote?.services : [];
            this.form.services.forEach(function (e, index) {
                value.push(index);
            });
            return value;
        }
    },
    methods: {
        setSelected: function (value) {
            // this.form.services = value.value;
        },
        openModal: function () {
            this.isOpen = true;
        },
        closeModal: function () {
            this.validations = [];
            this.isOpen = false;
            this.reset();
            this.editMode = false;
        },
        reset: function () {
            this.form = {
                date: null,
                description: null,
                services: [],
                user_id:null
            }
            this.servicesSelected = null
            this.disabled = false;
        },
        save: function (data) {
            data.services = this.value
            API().post('/quotes', data, {
                validateStatus: status => status >= 200 && status < 300 || status === 422
            })
                .then(response => {
                    if (response.status < 300) {
                        this.reset();
                        this.closeModal();
                        this.editMode = false;
                        this.getQuotes();
                    }
                    this.validations = response.data.errors;
                })
                .catch(error => {
                    console.log(error)
                });
        },
        edit: function (data) {
            this.form = Object.assign({}, data);
            this.editMode = true;
            this.openModal();
        },
        update: function (data) {
            API().put('/quotes/' + data.id, data)
                .then(response => {
                    this.getQuotes();
                })
                .catch(function (error) {
                    console.log(error);
                });
            this.reset();
            this.closeModal();
        },
        destroy: function (data) {
            if (!confirm('Are you sure want to remove?')) return;
            API().delete('/quotes/' + data.id, data)
                .then(response => {
                    this.getQuotes();
                })
                .catch(function (error) {
                    console.log(error);
                });
            this.reset();
            this.closeModal();
        },
        show: function (data) {
            this.form = Object.assign({}, data);
            API().get('/quotes/' + data.id)
                .then(response => {
                    this.servicesSelected = response.data.data.services;
                    this.quote = response.data.data;
                    this.disabled = true;
                    this.openModal();
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getQuotes: function (search = '', orderBy = 'id', filter = '') {
            API().get('/quotes', {
                params: {
                    search: search,
                    date: filter,
                    orderBy: orderBy
                }
            })
                .then(response => {
                    this.validations = [];
                    this.quotes = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getLeads: function () {
            API().get('/leads')
                .then(response => {
                    console.log( response.data.data);
                    this.leads = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        getServices: function () {
            API().get('/services')
                .then(response => {
                    this.services = response.data;
                    this.form.services = this.myServices;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
    },
    watch: {
        search(after, before) {
            this.getQuotes(after, this.order, this.filter);
        },
        order(after, before) {
            this.getQuotes(this.search, after, this.filter);
        },
        filter(after, before) {
            this.getQuotes(this.search, this.order, after);
        }
    },
    mounted() {
        this.getServices();
        this.getLeads();
        this.getQuotes();
    },
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>

