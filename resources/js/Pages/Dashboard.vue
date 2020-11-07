<template>
  <app-layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="card text-center">
          <div class="card-header">Laravel Test</div>
          <div class="card-body">
            <p class="card-text my-6">
              The test contais tree tables besides the registration ones:
              Customer, Number and Number Preferences. The system works lika a
              flow, it starts by registering a customer, after that a Number and
              finally a Number Preference. Note that when creating a number created, two
              Number Preferences are also created.
            </p>
            <p class="card-text my-6">
              It is possible see, edit and delete the records using the menu
              above.
            </p>
            <p class="card-text my-6">
              To start de flow use the button bellow.
            </p>
            <a class="btn btn-success" href="/customer/create?flow=true"
              >Begin</a
            >
          </div>
        </div>
      </div>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Layouts/AppLayout";

export default {
  components: {
    AppLayout,
  },
};
</script>
