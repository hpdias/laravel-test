<template>
  <app-layout>
    <div class="py-12">
      <h1 class="mb-4">Create Number Preference</h1>
      <div v-show="error" class="alert alert-danger" role="alert">
        {{error}}
      </div>
      <form form @submit.prevent="submit">
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="name">Number Preference</label>
            <select
              :disabled="flowNumberId"
              id="number_id"
              v-model="form.number_id"
              :class="errors.number_id ? 'form-control is-invalid' : 'form-control'"
            >
              <option v-for="numb in numbers" :key="numb.id" :value="numb.id">{{numb.number}}</option>
            </select>
            <div v-show="errors.number_id" class="text-danger">{{errors.number_id}}</div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="name">Name</label>
            <input
              type="text"
              :class="errors.name ? 'form-control is-invalid' : 'form-control'"
              id="name"
              v-model="form.name"
            />
            <div v-show="errors.name" class="text-danger">{{errors.name}}</div>
          </div>
        </div>
        <div class="form-row">
          <div class="col-md-12 mb-3">
            <label for="value">Value</label>
            <input
              type="text"
              :class="errors.value ? 'form-control is-invalid' : 'form-control'"
              id="name"
              v-model="form.value"
            />
            <div v-show="errors.value" class="text-danger">{{errors.value}}</div>
          </div>
        </div>
        <button type="submit" class="btn btn-primary">
          {{ this.numberPreference && this.numberPreference.id ? "Edit" : "Create" }} Number Preference
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
        number_id: "",
        name: "",
        value: "",
      },
    };
  },

  components: {
    AppLayout,
  },

  props: {
    numberPreference: Object,
    numbers: Array,
    error: String,
    flowNumberId: String
  },

  beforeMount() {

    //if this variable is not null, then it is comming from the flow, needs to set the number
    if (this.flowNumberId) {
      this.form.number_id = this.flowNumberId;
    }else
    if (this.numberPreference && this.numberPreference.id) {
      this.form = this.numberPreference;
    }
  },

  methods: {
    async submit() {
      if (this.numberPreference) {
        await this.$inertia.put("/number_preference/" + this.numberPreference.id, this.form).then((response) => {
          const { props = {} } = Inertia.page;

          //if have erros put then in a variable to show in the screen
          if(props.errors){
            this.errors = props.errors;
          }
        });
      } else {
        await this.$inertia.post("/number_preference", this.form).then((response) => {
          const { props = {} } = Inertia.page;

          //if have erros put then in a variable to show in the screen
          if(props.errors){
            this.errors = props.errors;
          }
        });
      }
    },
  },
};
</script>
