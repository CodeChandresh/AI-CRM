// resources/js/Pages/Activities/Index.vue

<template>
  <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-8 xl:p-8 2xl:p-8">
    <h1 class="text-3xl font-bold mb-4">Activity Feed</h1>

    <div class="flex flex-wrap justify-between mb-4">
      <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Search activities"
          class="w-full p-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        />
      </div>
      <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
        <select
          v-model="filterType"
          class="w-full p-2 text-sm text-gray-700 placeholder-gray-300 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
          <option value="">All</option>
          <option value="sentiment">Sentiment</option>
          <option value="type">Type</option>
        </select>
      </div>
    </div>

    <div class="flex flex-wrap justify-between mb-4">
      <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
        <button
          @click="filterSentiment"
          class="w-full p-2 text-sm text-white bg-blue-500 hover:bg-blue-700 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
          Filter by Sentiment
        </button>
      </div>
      <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
        <button
          @click="filterType"
          class="w-full p-2 text-sm text-white bg-blue-500 hover:bg-blue-700 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
          Filter by Type
        </button>
      </div>
    </div>

    <div class="flex flex-wrap justify-between mb-4">
      <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
        <button
          @click="resetFilters"
          class="w-full p-2 text-sm text-white bg-red-500 hover:bg-red-700 rounded-lg focus:ring-red-500 focus:border-red-500"
        >
          Reset Filters
        </button>
      </div>
    </div>

    <div class="flex flex-wrap justify-between mb-4">
      <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
        <p class="text-sm text-gray-700">Showing {{ activities.length }} activities</p>
      </div>
    </div>

    <div class="flex flex-wrap justify-between mb-4">
      <div class="w-full md:w-1/2 xl:w-1/3 mb-4 md:mb-0">
        <button
          @click="loadMoreActivities"
          class="w-full p-2 text-sm text-white bg-blue-500 hover:bg-blue-700 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
          Load More
        </button>
      </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
      <div
        v-for="(activity, index) in activities"
        :key="index"
        class="bg-white rounded-lg shadow-md p-4"
      >
        <h2 class="text-lg font-bold mb-2">{{ activity.title }}</h2>
        <p class="text-sm text-gray-700 mb-2">{{ activity.description }}</p>
        <p class="text-sm text-gray-700 mb-2">
          Sentiment: {{ activity.sentiment }}
        </p>
        <p class="text-sm text-gray-700 mb-2">
          Type: {{ activity.type }}
        </p>
        <button
          @click="viewActivity(activity.id)"
          class="w-full p-2 text-sm text-white bg-blue-500 hover:bg-blue-700 rounded-lg focus:ring-blue-500 focus:border-blue-500"
        >
          View Activity
        </button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchQuery: "",
      filterType: "",
      activities: [],
      currentPage: 1,
      perPage: 10,
    };
  },
  mounted() {
    this.loadActivities();
  },
  methods: {
    loadActivities() {
      axios
        .get("/api/activities", {
          params: {
            search: this.searchQuery,
            filter: this.filterType,
            page: this.currentPage,
            per_page: this.perPage,
          },
        })
        .then((response) => {
          this.activities = response.data.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    loadMoreActivities() {
      this.currentPage++;
      this.loadActivities();
    },
    filterSentiment() {
      this.filterType = "sentiment";
      this.loadActivities();
    },
    filterType() {
      this.filterType = "type";
      this.loadActivities();
    },
    resetFilters() {
      this.searchQuery = "";
      this.filterType = "";
      this.loadActivities();
    },
    viewActivity(id) {
      this.$inertia.visit(`/activities/${id}`);
    },
  },
};
</script>

<style scoped>
.grid {
  grid-template-columns: repeat(1, 1fr);
}

@media (min-width: 768px) {
  .grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (min-width: 1024px) {
  .grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (min-width: 1280px) {
  .grid {
    grid-template-columns: repeat(4, 1fr);
  }
}
</style>