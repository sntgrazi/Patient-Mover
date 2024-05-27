import axios from 'axios';

const baseURL = "http://localhost:8000/api"; 

const apiService = axios.create({ 
    baseURL, 
    headers: {
        'Content-Type': 'application/json'
    }
});

export default {
    login(credentials) {    
        return apiService.post('/login', credentials);
    }
};
