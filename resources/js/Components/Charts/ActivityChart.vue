// resources/js/Components/Charts/ActivityChart.vue

<template>
  <div>
    <h2 class="text-lg font-bold mb-4">Activity Volume and Sentiment Trend</h2>
    <div class="flex justify-center mb-4">
      <Recharts>
        <BarChart width={800} height={400} data={activityData}>
          <CartesianGrid strokeDasharray="3 3" />
          <XAxis dataKey="date" />
          <YAxis />
          <Tooltip />
          <Legend />
          <Bar dataKey="volume" fill="#8884d8" />
          <Bar dataKey="sentiment" fill="#82ca9d" />
        </BarChart>
      </Recharts>
    </div>
  </div>
</template>

<script>
import { BarChart, CartesianGrid, XAxis, YAxis, Tooltip, Legend, Bar } from 'recharts';
import Recharts from 'recharts-vue3';

export default {
  components: { Recharts, BarChart, CartesianGrid, XAxis, YAxis, Tooltip, Legend, Bar },
  props: {
    activityData: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style scoped>
.recharts-wrapper {
  max-width: 800px;
  margin: 0 auto;
}
</style>