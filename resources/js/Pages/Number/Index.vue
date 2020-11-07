<template>
  <app-layout>
    <div class="py-12">
      <h1 class="mb-4">Numbers</h1>
      <div v-show="error" class="alert alert-danger" role="alert">
        {{error}}
      </div>
      <a href="/number/create" class="my-2 btn btn-primary">
        <i class="fa fa-plus mr-2"></i>Add
      </a>
      <table class="table">
        <thead class="bg-primary text-white">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Customer</th>
            <th scope="col">Number</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="number in numbers" :key="number.id">
            <th scope="row">1</th>
            <td>{{ number.customer_name }}</td>
            <td>{{ number.number }}</td>
            <td>{{ number.status }}</td>
            <td>
              <a
                :href="'/number/' + number.id + '/edit'"
                alt="edit"
                class="mr-2"
              >
                <i class="fa fa-edit"></i>
              </a>
              <a
                href="/number/create"
                alt="Deletar"
                @click="delId = number.id"
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
    numbers: Array,
    error: String
  },

  methods: {
    async deleteRecord() {
      if (this.delId) {
        await this.$inertia
          .delete("/number/" + this.delId, this.form)
          .then((response) => {
            const { props = {} } = Inertia.page;
            this.delId = null;
          });
      }
    },
  },
};
</script>
