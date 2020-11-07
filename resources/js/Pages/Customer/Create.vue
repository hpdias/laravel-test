<template>
  <app-layout>
    <div class="py-12">
      <p class="mb-4 text-primary font-weight-bold font-size-titles" >Create Customers</p>
      <div v-show="error" class="alert alert-danger" role="alert">
        {{ error }}
      </div>
      <form form @submit.prevent="submit">
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="name">Name</label>
            <input
              :disabled="customer && customer.id"
              type="text"
              :class="errors.name ? 'form-control is-invalid' : 'form-control'"
              id="name"
              v-model="form.name"
            />
            <div v-show="errors.name" class="text-danger">
              {{ errors.name }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="document">Document</label>
            <input
              :disabled="customer && customer.id"
              type="text"
              :class="
                errors.document ? 'form-control is-invalid' : 'form-control'
              "
              id="document"
              v-model="form.document"
            />
            <div v-show="errors.document" class="text-danger">
              {{ errors.document }}
            </div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="status">Status</label>
            <select
              id="status"
              v-model="form.status"
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
        <button v-if="!flow" type="submit" class="btn btn-primary">
          {{ this.customer && this.customer.id ? "Edit" : "Create" }} Customer
        </button>
        <button v-else type="submit" class="btn btn-primary">
          Add Number <i class="fa fa-forward mr-2"></i>
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
        name: "",
        document: "",
        status: 0,
      },
    };
  },

  components: {
    AppLayout,
  },

  props: {
    customer: Object,
    statusOptions: Array,
    error: String,
    flow: Boolean,
  },

  beforeMount() {
    if (this.customer) {
      this.form = this.customer;
    }
  },

  methods: {
    async submit() {
      
      //if is flow then need continue sending the query param and go to the number create page
      if (this.flow) {
        await this.$inertia
          .post("/customer?flow=true", this.form)
          .then((response) => {
            const { props = {} } = Inertia.page;

            if (props.errors) {
              this.errors = props.errors;
            }
          });
      } else {

        //if is not flow and the customer.id exists, then it is a edition
        if (this.customer && this.customer.id) {
          await this.$inertia
            .put("/customer/" + this.customer.id, this.form)
            .then((response) => {
              const { props = {} } = Inertia.page;

              //if have erros put, in a variable to show in the screen
              if (props.errors) {
                this.errors = props.errors;
              }
            });
        } else {

          //other wise it is create
          await this.$inertia.post("/customer", this.form).then((response) => {
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
