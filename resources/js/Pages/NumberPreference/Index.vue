<template>
  <app-layout>
    <div class="py-12">
      <h1 class="mb-4">Numbers Preferences</h1>
      <a href="/number_preference/create" class="my-2 btn btn-primary">
        <i class="fa fa-plus mr-2"></i>Add
      </a>
      <table class="table">
        <thead class="bg-primary text-white">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Customer</th>
            <th scope="col">Number</th>
            <th scope="col">Name</th>
            <th scope="col">Value</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="numberPref in numbersPreferences" :key="numberPref.id">
            <th scope="row">1</th>
            <td>{{ numberPref.customer_name }}</td>
            <td>{{ numberPref.number_name }}</td>
            <td>{{ numberPref.name }}</td>
            <td>{{ numberPref.value }}</td>
            <td>
              <a
                :href="'/number_preference/' + numberPref.id + '/edit'"
                alt="edit"
                class="mr-2"
              >
                <i class="fa fa-edit"></i>
              </a>
              <a
                alt="Deletar"
                @click="delId = numberPref.id"
                data-toggle="modal"
                data-target="#delete"
                class="ml-2"
              >
                <i class="fa fa-trash"></i>
              </a>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <ConfirmModal
      name="delete"
      :title="'Confirm'"
      :text="deleteModalText"
      button="danger"
      @confirm="deleteRecord"
    ></ConfirmModal>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";
import ConfirmModal from "@/Components/ConfirnModal";
import { Inertia } from "@inertiajs/inertia";

export default {
  data() {
    return {
      delId: null,
      deleteModalText: "Are you sure you want to delete this record?",
    };
  },

  components: {
    AppLayout,
    ConfirmModal,
  },

  beforeMount (){
    $("#delete").modal("hide");
  },

  props: {
    numbersPreferences: Array,
  },

  methods: {
    async deleteRecord() {
      if (this.delId) {
        await this.$inertia
          .delete("/number_preference/" + this.delId, this.form)
          .then((response) => {
            this.delId = null;
          });
      }
    },
  },
};
</script>
