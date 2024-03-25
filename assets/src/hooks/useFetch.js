import { useState, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';

/**
 * @param endpoint endpoint to fetch data from
 * @returns { data, error, loading }
 */
export function useFetch(path, method = 'GET', body = null){

    /*const url = new URL( window.location.origin + '/wp-admin/admin-ajax.php' );
    url.searchParams.append( 'action', ajax_action );*/

    const [data, setData] = useState(null);
    const [error, setError] = useState(null);
    const [loading, setLoading] = useState(true);

    useEffect(() => {
        apiFetch( {
            url: path,
            method: method,
            data: body
        }).then(res => {
            setData(res.response)
            setLoading(false)
        }).catch(error => setError(error));
    }, [path]);

    return { data, error, loading };
}