<template>
<v-app>
    <v-card
        elevation="2"
    >
        <div :class="$vuetify.theme.dark ? 'grey darken-3' : 'grey lighten-4'">
            <v-card-title
                :class="$vuetify.theme.dark ? 'grey darken-3 title font-weight-regular justify-space-between' : 'grey lighten-4 title font-weight-regular justify-space-between' "
            >
                <span> Pedidos </span>
                <v-avatar
                    color="primary lighten-2"
                    class="subheading white--text"
                    size="32"
                ></v-avatar>
            </v-card-title>
        </div>
        <v-card-text v-if="loading">
            <v-sheet
                v-for="index in 10"
                :key="index"
            >
                <v-skeleton-loader
                    class="pa-md-1 mx-lg-auto"
                    max-width="999"
                    max-height="100"
                    v-bind="attrs"
                    type="list-item-avatar-three-line, card-heading, actions"
                ></v-skeleton-loader>
            </v-sheet>
        </v-card-text>
        <v-card-text v-if="!loading && $store.state.orders.length == 0">
            
            <div class="card-body">                   
                Não existe ordens para entrega!
            </div>
        </v-card-text>
        <v-card-text v-if="!loading && $store.state.orders.length > 0">
            <v-card 
                class="pa-md-4 mx-lg-auto mb-2"
                elevation="2"
                v-for="order in $store.state.orders"
                :key="order.orderId"
            >
                <div class="card-body">                   
                    <div class="d-flex justify-space-between caption">
                        <div class="font-weight-black mr-3">
                            <div class="font-weight-medium">
                                <v-avatar
                                    size="64"
                                    class="mr-5"
                                >
                                    <v-img
                                        :src="require('../assets/images/ifood.jpg')"
                                        alt="iFood"
                                    />
                                </v-avatar>
                            </div>
                        </div>
                        <div class="font-weight-black">
                            <div class="font-weight-medium">
                                Pedido: {{order.displayId}}
                            </div>
                            <div class="font-weight-medium">
                                Status: {{order.fullCode}}
                            </div>
                        </div>
                        <div class="font-weight-black">
                            <div class="font-weight-medium">
                                Status: {{order.fullCode}} -
                            </div>
                            <div class="font-weight-medium">
                                Status: {{order.orderType}} -
                            </div>
                        </div>
                        <div class="font-weight-black">
                            <div class="font-weight-medium">
                                Valor: {{order.orderAmount ? formatCurrency(order.orderAmount) : '-'}} -
                            </div>
                        </div>
                        <div>
                            <div class="grey--text text-darken-1">
                                <v-btn
                                    class="ma-2"
                                    :loading="loading"
                                    :disabled="loading"
                                    color="success"
                                    @click="confirmOrder(order.orderId)"
                                    small
                                >
                                    SOLICITAR ENTREGA
                                </v-btn>
                                <v-btn
                                    class="ma-2"
                                    color="primary"
                                    dark
                                    v-bind="attrs"
                                    @click="showDetails(order)"
                                    small
                                >
                                    DETALHES
                                </v-btn>
                                <!-- <v-btn
                                    class="ma-2"
                                    :loading="loading"
                                    :disabled="loading"
                                    color="error"
                                    @click="loader = 'loading'"
                                    small
                                >
                                    Cancelar
                                </v-btn> -->
                            </div>
                        </div>
                    </div>
                </div>
            </v-card>
        </v-card-text>
    </v-card>
    <modal-component v-if="$store.state.sheet"/>
</v-app>
</template>

<script>
import ModalComponent from "../components/Modal.vue";
    export default {
        components: {
            ModalComponent
        },
        created(){
            
        },
        mounted() {
            console.log('Component mounted.')
            if (this.$store.state.orders) {
                setTimeout(() => {
                    this.getOrders();
                }, 2000);
            }
        },
        methods: {
            setDrawer(){
                this.$store.commit('toggleDrawer', this.$store.state.drawer)
            },
            getOrders() {
                if (this.$store.state.orders) {
                    this.loading = !this.loading;
                }
                if (this.$store.state.orders.length === 0) {
                    console.log("Vazio");
                }
            },
            formatCurrency(value){
                return (value).toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL',
                });
            }, 
            confirmOrder(id){
                this.$store.dispatch('confirmOrder', id)
            },
            showDetails(order) {
                console.log("Sheet", this.$store.state.sheet);
                this.$store.dispatch('showDetail', { key: 'orderDetails', data: order})
            }
        },
        data(){
            return{
                loading: true,
                loader: null,
                attrs: {
                    class: 'mb-2',
                    boilerplate: true,
                    elevation: 2,
                },
                sheet: false
            }
        },
        watch: {
            loader () {
                const l = this.loader
                this[l] = !this[l]

                setTimeout(() => (this[l] = false), 3000)

                this.loader = null
            },
        }
    }
</script>
