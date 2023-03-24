<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Gestión de vendedores
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg px-8 py-8">
                    <button v-if="can('sellers.store')" @click="openModal()"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded my-3">Crear nuevo
                        vendedor</button>

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
                            <option value="first_name">Nombre</option>
                            <option value="email">Email</option>
                            <option value="dni">D.N.I</option>
                        </select>
                    </div>
                    <table class="table-reponsive w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2 w-20">No.</th>
                                <th class="px-4 py-2">Nombre</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">D.N.I</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="row in sellers">
                                <td class="border px-4 py-2">{{ row.id }}</td>
                                <td class="border px-4 py-2">{{ row.first_name }} {{ row.last_name }}</td>
                                <td class="border px-4 py-2">{{ row.email }}</td>
                                <td class="border px-4 py-2">{{ row.dni }}</td>
                                <td class="border px-4 py-2">
                                    <button v-if="can('sellers.update')" @click="edit(row)"
                                        class="bg-yellow-500 hover:bg-yellow-700 mx-2 text-white font-bold py-2 px-4 rounded">Editar</button>
                                    <button v-if="can('sellers.show')" @click="show(row)"
                                        class="bg-orange-500 hover:bg-orange-700 mx-2 text-white font-bold py-2 px-4 rounded">Ver</button>
                                    <button v-if="can('sellers.delete')" @click="destroy(row)"
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
                                                <label for="first_name"
                                                    class="block text-gray-700 text-sm font-bold mb-2">Nombre:</label>
                                                <input type="text"
                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                    id="first_name" placeholder="Ingrese nombre" required
                                                    :disabled="disabled == true" v-model="form.first_name">
                                                <ShowErrors v-if="validations.first_name" :errors="validations.first_name">
                                                </ShowErrors>
                                            </div>
                                            <div class="mb-4">
                                                <label for="last_name"
                                                    class="block text-gray-700 text-sm font-bold mb-2">Apellido:</label>
                                                <input type="text"
                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                    id="last_name" v-model="form.last_name" required
                                                    placeholder="Ingrese apellido" :disabled="disabled == true">
                                                <ShowErrors v-if="validations.last_name" :errors="validations.last_name">
                                                </ShowErrors>
                                            </div>
                                            <div class="mb-4">
                                                <label for="dni"
                                                    class="block text-gray-700 text-sm font-bold mb-2">D.N.I:</label>
                                                <input type="number"
                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                    id="dni" v-model="form.dni" min="1" required placeholder="Ingrese dni"
                                                    :disabled="disabled == true">
                                                <ShowErrors v-if="validations.dni" :errors="validations.dni"></ShowErrors>
                                            </div>
                                            <div class="mb-4">
                                                <label for="email"
                                                    class="block text-gray-700 text-sm font-bold mb-2">Correo:</label>
                                                <input
                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                    id="email" v-model="form.email" type="email" required
                                                    :disabled="disabled == true" placeholder="Ingrese email  ">

                                                <ShowErrors v-if="validations.email" :errors="validations.email">
                                                </ShowErrors>
                                            </div>
                                            <div class="mb-4" v-show="!editMode && !disabled">
                                                <label for="password"
                                                    class="block text-gray-700 text-sm font-bold mb-2">Contraseña:</label>
                                                <input
                                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                    id="password" v-model="form.password" type="password" required
                                                    placeholder="Ingrese contraseña">
                                                <ShowErrors v-if="validations.password" :errors="validations.password">
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
import { API, hasPermission } from "@/helper"

export default {
    components: {
        AppLayout,
        InputSearch,
        Welcome,
        ShowErrors
    },
    props: ['data', 'errors'],
    data() {
        return {
            editMode: false,
            isOpen: false,
            form: {
                first_name: null,
                last_name: null,
                dni: null,
                email: null,
                password: null,
            },
            sellers: [],
            validations: [],
            search: '',
            order: 'id',
            disabled: false
        }
    },
    methods: {
        openModal: function () {
            this.isOpen = true;
        },
        closeModal: function () {
            this.isOpen = false;
            this.reset();
            this.editMode = false;
        },
        reset: function () {
            this.form = {
                first_name: null,
                last_name: null,
                dni: null,
                email: null,
                password: null,
            }
            this.validations = []
            this.disabled = false;
        },
        save: function (data) {
            API().post('/sellers', data, {
                validateStatus: status => status >= 200 && status < 300 || status === 422
            })
                .then(response => {
                    if (response.status < 300) {
                        this.reset();
                        this.closeModal();
                        this.editMode = false;
                        this.getSellers();
                    }
                    else {
                        this.validations = response.data.errors;
                    }
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
            API().put('/sellers/' + data.id, data, {
                validateStatus: status => status >= 200 && status < 300 || status === 422
            })
                .then(response => {
                    if (response.status < 300) {
                        this.getSellers();
                        this.reset();
                        this.closeModal();
                    } else {
                        this.validations = response.data.errors;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        destroy: function (data) {
            if (!confirm('Are you sure want to remove?')) return;
            API().delete('/sellers/' + data.id, data)
                .then(response => {
                    this.getSellers();
                })
                .catch(function (error) {
                    console.log(error);
                });
            this.reset();
            this.closeModal();
        },
        show: function (data) {
            this.form = Object.assign({}, data);
            this.disabled = true;
            this.openModal();
        },
        getSellers: function (search = '', orderBy = 'id') {
            API().get('/sellers', {
                params: {
                    search: search,
                    orderBy: orderBy
                }
            })
                .then(response => {
                    this.sellers = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        can: function (permission) {
            return hasPermission(permission);
        }
    },
    watch: {
        search(after, before) {
            this.getSellers(after, this.order);
        },
        order(after, before) {
            this.getSellers(this.search, after);
        }
    },
    mounted() {
        this.getSellers();
    },
}
</script>