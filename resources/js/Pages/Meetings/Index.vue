<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-semibold text-gray-800">Meetings & Calendar</h1>
      <button @click="showModal = true" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Schedule Meeting
      </button>
    </div>

    <!-- Meetings List -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Start Time</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">End Time</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-if="meetings.length === 0">
            <td colspan="5" class="px-6 py-4 text-center text-gray-500">No meetings scheduled yet.</td>
          </tr>
          <tr v-for="meeting in meetings" :key="meeting.id">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ meeting.title }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ meeting.description || '-' }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(meeting.start_time) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ formatDate(meeting.end_time) }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
              <button @click="deleteMeeting(meeting.id)" class="text-red-600 hover:text-red-900">Delete</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Create Meeting Modal -->
    <div v-if="showModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showModal = false"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <form @submit.prevent="createMeeting">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="title">Title</label>
                <input v-model="form.title" type="text" id="title" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
                <textarea v-model="form.description" id="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"></textarea>
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="start_time">Start Time</label>
                <input v-model="form.start_time" type="datetime-local" id="start_time" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              </div>
              <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="end_time">End Time</label>
                <input v-model="form.end_time" type="datetime-local" id="end_time" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
              </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
              <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                Save
              </button>
              <button type="button" @click="showModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                Cancel
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      meetings: [],
      showModal: false,
      form: {
        title: '',
        description: '',
        start_time: '',
        end_time: ''
      }
    };
  },
  mounted() {
    this.fetchMeetings();
  },
  methods: {
    async fetchMeetings() {
      try {
        const response = await axios.get('/api/meetings');
        this.meetings = response.data;
      } catch (error) {
        console.error('Error fetching meetings:', error);
      }
    },
    async createMeeting() {
      try {
        await axios.post('/api/meetings', this.form);
        this.showModal = false;
        this.form = { title: '', description: '', start_time: '', end_time: '' };
        this.fetchMeetings();
      } catch (error) {
        console.error('Error creating meeting:', error);
        alert('Failed to create meeting. Please check the inputs.');
      }
    },
    async deleteMeeting(id) {
      if (confirm('Are you sure you want to delete this meeting?')) {
        try {
          await axios.delete(`/api/meetings/${id}`);
          this.fetchMeetings();
        } catch (error) {
          console.error('Error deleting meeting:', error);
        }
      }
    },
    formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleString();
    }
  }
};
</script>
