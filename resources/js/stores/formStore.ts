import { defineStore } from 'pinia'
import type { FormData } from '@/types/form'
import { api } from '@/lib/api' 

export const useFormStore = defineStore('form', {
  state: () => ({
    forms: [] as FormData[]
  }),
  actions: {
    addForm(form: FormData): Promise<Response> {
      this.forms.push(form)
      return api.post('/admin/form/store', form)
    },
    async updateForm(form: FormData): Promise<boolean> {
      try {
        const response = await api.put(`/admin/form/${form.id}`, form)
        if (response.ok) {
          // Update the form in the local state
          const idx = this.forms.findIndex(f => (f as any).id === form.id)
          if (idx !== -1) {
            this.forms[idx] = form
          }
          return true
        }
        return false
      } catch {
        return false
      }
    },
    async removeForm(id: number): Promise<boolean> {
      try {
        const response = await api.delete(`/admin/form/delete/${id}`)
        if (response.ok) {
          this.forms = this.forms.filter(f => (f as any).id !== id)
          return true
        }
        return false
      } catch {
        return false
      }
    }
  }
})