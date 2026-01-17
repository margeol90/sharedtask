import AxiosMockAdapter from 'axios-mock-adapter'
import axios from 'axios'

export const setupAxiosMock = () => {
  const mock = new AxiosMockAdapter(axios)
  return mock
}
