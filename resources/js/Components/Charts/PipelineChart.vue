// resources/js/Components/Charts/PipelineChart.vue

<template>
  <div>
    <h2 class="text-lg font-bold mb-4">Pipeline Stage Distribution</h2>
    <Recharts>
      <BarChart width={800} height={400} data={data}>
        <CartesianGrid strokeDasharray="3 3" />
        <XAxis dataKey="stage" />
        <YAxis />
        <Tooltip />
        <Legend />
        <Bar dataKey="count" fill="#8884d8" />
      </BarChart>
    </Recharts>
  </div>
</template>

<script>
import { BarChart, CartesianGrid, XAxis, YAxis, Tooltip, Legend, Bar } from 'recharts';

export default {
  components: {
    BarChart,
    CartesianGrid,
    XAxis,
    YAxis,
    Tooltip,
    Legend,
    Bar,
  },
  props: {
    data: {
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