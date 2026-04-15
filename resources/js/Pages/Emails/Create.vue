// resources/js/Pages/Emails/Create.vue

<template>
  <div class="max-w-md mx-auto p-8 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold mb-4">Compose Email</h2>
    <form @submit.prevent="sendEmail">
      <div class="mb-4">
        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
        <input
          id="subject"
          v-model="email.subject"
          type="text"
          class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Enter email subject"
        />
      </div>
      <div class="mb-4">
        <label for="body" class="block text-sm font-medium text-gray-700">Body</label>
        <textarea
          id="body"
          v-model="email.body"
          class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Enter email body"
        />
      </div>
      <div class="mb-4">
        <label for="tone" class="block text-sm font-medium text-gray-700">Tone</label>
        <select
          id="tone"
          v-model="email.tone"
          class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
        >
          <option value="neutral">Neutral</option>
          <option value="friendly">Friendly</option>
          <option value="professional">Professional</option>
        </select>
      </div>
      <div class="mb-4">
        <label for="schedule" class="block text-sm font-medium text-gray-700">Schedule Send</label>
        <input
          id="schedule"
          v-model="email.schedule"
          type="datetime-local"
          class="block w-full p-2 mt-1 text-sm text-gray-700 border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
        />
      </div>
      <button
        type="submit"
        class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded"
      >
        Send Email
      </button>
    </form>
    <div class="mt-4">
      <h3 class="text-lg font-bold mb-2">Email Preview</h3>
      <div class="bg-gray-100 p-4 rounded">
        <p class="text-sm text-gray-700">
          {{ email.subject }}<br />
          {{ email.body }}
        </p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      email: {
        subject: '',
        body: '',
        tone: 'neutral',
        schedule: '',
      },
    }
  },
  methods: {
    sendEmail() {
      // Send email using Laravel's email API
      axios.post('/api/emails', this.email)
        .then(response => {
          console.log(response.data);
          this.email = {
            subject: '',
            body: '',
            tone: 'neutral',
            schedule: '',
          };
        })
        .catch(error => {
          console.error(error);
        });
    },
  },
}
</script>

<style scoped>
/* Add some basic styling to the component */
.max-w-md {
  max-width: 400px;
}

.mx-auto {
  margin-left: auto;
  margin-right: auto;
}

.p-8 {
  padding: 2rem;
}

.bg-white {
  background-color: #fff;
}

.rounded-lg {
  border-radius: 1rem;
}

.shadow-md {
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.text-lg {
  font-size: 1.125rem;
}

.font-bold {
  font-weight: 600;
}

.mb-4 {
  margin-bottom: 1rem;
}

.block {
  display: block;
}

.text-sm {
  font-size: 0.875rem;
}

.font-medium {
  font-weight: 500;
}

.text-gray-700 {
  color: #3f3f3f;
}

.border-gray-300 {
  border-color: #e2e2e2;
}

.rounded-md {
  border-radius: 0.375rem;
}

.focus:ring-indigo-500 {
  ring-color: #6b46c1;
}

.focus:border-indigo-500 {
  border-color: #6b46c1;
}

.bg-indigo-500 {
  background-color: #6b46c1;
}

.hover:bg-indigo-700 {
  background-color: #5a3d9b;
}

.text-white {
  color: #fff;
}

.py-2 {
  padding-top: 0.5rem;
  padding-bottom: 0.5rem;
}

.px-4 {
  padding-left: 1rem;
  padding-right: 1rem;
}

.rounded {
  border-radius: 0.375rem;
}

.mt-4 {
  margin-top: 1rem;
}

.text-lg {
  font-size: 1.125rem;
}

.font-bold {
  font-weight: 600;
}

.mb-2 {
  margin-bottom: 0.5rem;
}

.bg-gray-100 {
  background-color: #f7f7f7;
}

.p-4 {
  padding: 1rem;
}

.rounded {
  border-radius: 0.375rem;
}

.text-sm {
  font-size: 0.875rem;
}

.text-gray-700 {
  color: #3f3f3f;
}
</style>