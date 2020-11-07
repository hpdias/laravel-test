<template>
  <app-layout>
    <div class="py-12">
      <p class="mb-4 text-primary font-weight-bold font-size-titles" >Customers</p>
      <div v-show="error" class="alert alert-danger" role="alert">
        {{error}}
      </div>
      <a href="/customer/create" class="my-2 btn btn-primary">
        <i class="fa fa-plus mr-2"></i>Add
      </a>
      <table class="table">
        <thead class="bg-primary text-white">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Document</th>
            <th scope="col">Status</th>
            <th scope="col">Count Numbers</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="customer in customers" :key="customer.id">
            <th scope="row">1</th>
            <td>{{ customer.name }}</td>
            <td>{{ customer.document }}</td>
            <td>{{ customer.status }}</td>
            <td>{{ customer.count_numbers }}</td>
            <td>
              <a
                :href="'/customer/' + customer.id + '/edit'"
                alt="edit"
                class="mr-2"
              >
                <i class="fa fa-edit"></i>
              </a>
              <a
                href="/customer/create"
                alt="Deletar"
                @click="delId = customer.id"
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
    customers: Array,
    error: String
  },

  methods: {
    async deleteRecord() {
      if (this.delId) {
        await this.$inertia
          .delete("/customer/" + this.delId, this.form)
          .then((response) => {
            const { props = {} } = Inertia.page;
            this.delId = null;
          });
      }
    },
  },
};
</script>
