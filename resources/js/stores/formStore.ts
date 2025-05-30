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
      return api.post('/form/store', form)
    },
    async removeForm(id: number): Promise<boolean> {
      try {
        const response = await api.delete(`/form/delete/${id}`)
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