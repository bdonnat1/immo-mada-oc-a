import axios from "axios";
export default axios.create({
    baseURL: "http://localhost/dna_smcm",
    headers: {
        "Content-type": "application/json",
        "Access-Control-Allow-Origin": "*",
        Authorization: "Bearer EEEEEEEEEEEEEEEEEEEEEEEEEEEEEEE"
    }
});