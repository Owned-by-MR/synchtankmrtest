<template>
  <div>
    <h1>Track Manager</h1>

    <form @submit.prevent="submitTrack">
      <input v-model="track.title" placeholder="Title" required />
      <input v-model="track.artist" placeholder="Artist" required />
      <input v-model="track.duration" placeholder="Duration (mm:ss)" required />
      <input v-model="track.isrc" placeholder="ISRC (UK-ABC-12-12345)" />
      <button type="submit">{{ editingTrackId ? 'Update' : 'Add' }} Track</button>
      <button type="button" v-if="editingTrackId" @click="cancelEdit">Cancel</button>
    </form>

    <h2>Track List</h2>

    <table border="1" cellspacing="0" cellpadding="5">
      <thead>
        <tr>
          <th>Title</th>
          <th>Artist</th>
          <th>Duration</th>
          <th>ISRC</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="tracking in tracks" :key="tracking.id">
          <td>{{ tracking.title }}</td>
          <td>{{ tracking.artist }}</td>
          <td>{{ formatDuration(tracking.duration) }}</td>
          <td>{{ tracking.isrc || '-' }}</td>
          <td>
            <button @click="editTrack(tracking)">Edit</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const tracks = ref([])
const track = ref({
  title: '',
  artist: '',
  duration: '',
  isrc: ''
})
const editingTrackId = ref(null)

const fetchTracks = async () => {
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/tracks')
    tracks.value = response.data
  } catch (err) {
    console.error('Failed to fetch tracks:', err)
  }
}

const submitTrack = async () => {
  const durationParts = track.value.duration.split(':')

  if (
    durationParts.length !== 2 ||
    isNaN(durationParts[0]) ||
    isNaN(durationParts[1])
  ) {
    alert("Duration must be in mm:ss format")
    return
  }

  const mm = parseInt(durationParts[0])
  const ss = parseInt(durationParts[1])

  if (ss >= 60 || mm < 0 || ss < 0) {
    alert("Minutes and seconds must be non-negative, and seconds less than 60")
    return
  }

  const durationInSeconds = mm * 60 + ss

  const loading = {
    ...track.value,
    duration: durationInSeconds
  }

  try {
    if (editingTrackId.value) {
      await axios.put(`http://127.0.0.1:8000/api/tracks/${editingTrackId.value}`, loading)
    } else {
      await axios.post('http://127.0.0.1:8000/api/tracks', loading)
    }

    resetForm()
    await fetchTracks()
  } catch (err) {
    console.error('Error submitting track:', err)
    alert('Failed to submit track. See console for details.')
  }
}

const editTrack = (tracking) => {
  editingTrackId.value = tracking.id
  const mm = Math.floor(tracking.duration / 60).toString().padStart(2, '0')
  const ss = (tracking.duration % 60).toString().padStart(2, '0')

  track.value = {
    title: tracking.title,
    artist: tracking.artist,
    duration: `${mm}:${ss}`,
    isrc: tracking.isrc || ''
  }
}

const cancelEdit = () => {
  resetForm()
}

const resetForm = () => {
  editingTrackId.value = null
  track.value = { title: '', artist: '', duration: '', isrc: '' }
}

const formatDuration = (seconds) => {
  const mm = Math.floor(seconds / 60).toString().padStart(2, '0')
  const ss = (seconds % 60).toString().padStart(2, '0')
  return `${mm}:${ss}`
}

onMounted(fetchTracks)
</script>

<style scoped>
input {
  display: block;
  margin-bottom: 0.5rem;
}

table {
  margin-top: 1rem;
  border-collapse: collapse;
  width: 100%;
}

th, td {
  padding: 0.5rem;
  text-align: left;
}

button {
  margin-right: 0.5rem;
}
</style>
