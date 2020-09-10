
import React from "react";
import axios from 'axios'
export async function postData(url: Request | string = '', data: any = {}, token: string | null) {
    const response = await fetch(url, {
        method: 'POST', // *GET, POST, PUT, DELETE, etc.
        mode: 'cors', // no-cors, *cors, same-origin
        cache: 'no-cache', // *default, no-cache, reload, force-cache, only-if-cached
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + token
        },
        redirect: 'follow',
        referrerPolicy: 'no-referrer',
        body: JSON.stringify(data)
    });

    if (!response.ok) {
        return null;
    }

    return await response.json(); // parses JSON response into native JavaScript objects
}


export function deleteMethod(url: string = '', token: string | null) {
    axios.delete(url);
}
export async function getData(url: Request | string = '', token: string | null) {
    const response = await fetch(url, {
        headers: {
            'Authorization': 'Bearer ' + token
            // 'Content-Type': 'application/x-www-form-urlencoded',
        },
    })

    if (!response.ok) {
        return null;
    }

    return await response.json(); // parses JSON response into native JavaScript objects
}