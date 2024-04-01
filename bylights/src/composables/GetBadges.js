import { ref } from 'vue'

const GetBadges = () => {
    const badges = ref([])
    const error = ref(null)

    const fetchBadges = async () => {
        try {
            let response = await fetch('http://localhost:3000/badges');
            if(!response.ok){
                throw Error('data not available')
            }
            badges.value = await response.json()
            } 
        catch (err) {
            error.value = err.message
            console.log(error.value)
        }
    }

    return{ badges, error, fetchBadges}
}

export default GetBadges