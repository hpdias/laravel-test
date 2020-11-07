<template>
  <app-layout>
    <div class="py-12">
      <p class="mb-4 text-primary font-weight-bold font-size-titles" >Create Numbers</p>
      <div v-show="error" class="alert alert-danger" role="alert">
        {{ error }}
      </div>
      <form form @submit.prevent="submit">
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="name">Customer</label>
            <select
              :disabled="(number && number.id) || flowCustomerId"
              id="customer_id"
              v-model="form.customer_id"
              :class="
                errors.customer_id ? 'form-control is-invalid' : 'form-control'
              "
            >
              <option v-for="cust in customers" :key="cust.id" :value="cust.id">
                {{ cust.name }}
              </option>
            </select>
            <div v-show="errors.customer_id" class="text-danger">
              {{ errors.customer_id }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="number">Number</label>
            <input
              type="text"
              :disabled="number && number.id"
              :class="
                errors.number ? 'form-control is-invalid' : 'form-control'
              "
              id="number"
              v-model="form.number"
            />
            <div v-show="errors.number" class="text-danger">
              {{ errors.number }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="status">Status</label>
            <select
              id="status"
              v-model="form.status"
              :value="form.status"
              :class="
                errors.status ? 'form-control is-invalid' : 'form-control'
              "
            >
              <option
                v-for="(option, key) in statusOptions"
                :key="key"
                :value="key"
              >
                {{ option }}
              </option>
            </select>
            <div v-show="errors.status" class="text-danger">
              {{ errors.status }}
            </div>
          </div>
        </div>
        <button v-if="!flowCustomerId" type="submit" class="btn btn-primary">
          {{ this.number && this.number.id ? "Edit" : "Create" }} Number
        </button>
        <button v-else type="submit" class="btn btn-primary ml-4">
          Add Number Preferences <i class="fa fa-forward mr-2"></i>
        </button>
      </form>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import Welcome from "@/Jetstream/Welcome";
import { Inertia } from "@inertiajs/inertia";

export default {
  data: function () {
    return {
      errors: [],
      form: {
        customer_id: "",
        number: "",
        status: 0,
      },
    };
  },

  components: {
    AppLayout,
  },

  props: {
    customers: Array,
    number: Object,
    statusOptions: Object,
    error: String,
    flowCustomerId: String,
  },

  beforeMount() {
    //if this variable is not null, then it is comming from the flow, need set the customer
    if (this.flowCustomerId) {
      this.form.customer_id = this.flowCustomerId;
      this.form.status = 1;
    }else 
    if (this.number) {
      this.form = this.number;
    }
  },

  methods: {
    async submit() {

      //if is flow then need continue sending the query param and go to the number create page
      if (this.flowCustomerId) {
        await this.$inertia.post("/number?customer_id=" + this.form.customer_id, this.form).then((response) => {
            const { props = {} } = Inertia.page;

            //if have erros put, in a variable to show in the screen
            if (props.errors) {
              this.errors = props.errors;
            }
          });
      } else {

        //if is not flow and the customer.id exists, then it is a edition
        if (this.number && this.number.id) {
          await this.$inertia
            .put("/number/" + this.number.id, this.form)
            .then((response) => {
              const { props = {} } = Inertia.page;

              //if have erros, put in a variable to show in the screen
              if (props.errors) {
                this.errors = props.errors;
              }
            });
        } else {

          //other wise it is create
          await this.$inertia.post("/number", this.form).then((response) => {
            const { props = {} } = Inertia.page;

            //if have erros put, in a variable to show in the screen
            if (props.errors) {
              this.errors = props.errors;
            }
          });
        }
      }
    },
  },
};
</script>
