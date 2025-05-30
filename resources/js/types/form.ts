export interface ValidationRules {
  min?: number
  max?: number
  regex?: string
  email?: boolean
  number?: boolean
}

export interface FormField {
  type: string
  name: string
  label: string
  placeholder?: string
  required: boolean
  options?: string[]
  validation_rules: ValidationRules
}

export interface FormData {
  title: string
  method: string
  action: string
  fields: FormField[]
}