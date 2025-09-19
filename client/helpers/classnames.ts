import concatClasses from 'classnames'
import type { ArgumentArray } from 'classnames'
import { twMerge } from 'tailwind-merge'

/**
 * Efficiently merge Tailwind CSS classes without style conflicts.
 * It also suports conditional classes!
 */
export default function cn(...classnames: ArgumentArray) {
  return twMerge(
    concatClasses(classnames)
  )
}
