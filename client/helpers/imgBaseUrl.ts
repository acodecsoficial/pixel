import { siteBaseURL } from '@/services/api'

export default function imgBaseUrl(url: string) {
  if (/^https?/i.test(url)) {
    return url
  }
  url = !url.startsWith('/') ? `/${url}` : url
  return `${siteBaseURL}/storage${url}`
}
