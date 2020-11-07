<template>
  <div
    class="modal fade"
    :id="name"
    tabindex="-1"
    role="dialog"
    aria-labelledby="exampleModalLabel"
    aria-hidden="true"
  >
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{ title }}</h5>
          <button
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
          >
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <form form @submit.prevent="submit">
          <div class="modal-body">
            <div class="form-row">
              <div class="col-md-12 mb-3">
                <label for="name">Name</label>
                <input
                  type="text"
                  class="form-control"
                  id="name"
                  v-model="form.name"
                />
                <div class="valid-feedback">Looks good!</div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-12 mb-3">
                <label for="document">Document</label>
                <input
                  type="text"
                  class="form-control"
                  id="document"
                  v-model="form.document"
                  required
                />
                <div class="valid-feedback">Looks good!</div>
              </div>
            </div>
            <div class="form-row">
              <div class="col-md-12 mb-3">
                <label for="status">Status</label>
                <select
                  id="status"
                  v-model="form.status"
                  class="form-control"
                  required
                >
                  <option value="1">New</option>
                  <option value="2">Active</option>
                  <option value="3">Suspended</option>
                  <option value="4">Cancelled</option>
                </select>
                <div class="valid-feedback">Looks good!</div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="close">
              Close
            </button>
            <button
              type="submit"
              @click="$emit('confirm')"
              class="btn btn-primary"
            >
              Confirm
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        name: "",
        document: "",
        status: 0,
      },
      customer: null,
    };
  },

  props: ["name", "title"],

  methods: {
    async submit() {
      if (this.customer) {
        await axios
          .put("api/customer/" + this.customer.id, this.form)
          .then((response) => {
            console.log(response);
          });
      } else {
        await axios.post("api/customer", this.form).then((response) => {
          console.log(response);
        });
      }
    },

    close() {
      this.clearData();
      $("#" + this.name).modal("hide");
    },

    clearData() {
      this.form = {
        name: "",
        document: "",
        status: 0,
      };
    },
  },
};
</script>
