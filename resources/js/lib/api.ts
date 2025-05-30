function getCsrfToken() {
  const meta = document.querySelector('meta[name="csrf-token"]')
  return meta ? meta.getAttribute('content') : ''
}

export const api = {
  post: (url: string, data: any): Promise<Response> =>
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken() || '',
      },
      credentials: 'same-origin',
      body: JSON.stringify(data),
    }),
  put: (url: string, data: any): Promise<Response> =>
    fetch(url, {
      method: 'PUT',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken() || '',
      },
      credentials: 'same-origin',
      body: JSON.stringify(data),
    }),
  get: (url: string): Promise<Response> =>
    fetch(url, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken() || '',
      },
      credentials: 'same-origin',
    }),
  delete: (url: string, data?: any): Promise<Response> =>
    fetch(url, {
      method: 'post',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': getCsrfToken() || '',
      },
      credentials: 'same-origin',
      body: data ? JSON.stringify(data) : undefined,
    }),
}

export const apiWithAuth = {
  post: (url: string, data: any, token: string): Promise<Response> =>
    fetch(url, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`,
      },
      body: JSON.stringify(data),
    }),
  get: (url: string, token: string): Promise<Response> =>
    fetch(url, {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`,
      },
    }),
  delete: (url: string, token: string, data?: any): Promise<Response> =>
    fetch(url, {
      method: 'DELETE',
      headers: {
        'Content-Type': 'application/json',
        Authorization: `Bearer ${token}`,
      },
      body: data ? JSON.stringify(data) : undefined,
    }),
}