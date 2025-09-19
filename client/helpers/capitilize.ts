export function upperFirstLetter(string: string) {
  return string.charAt(0).toUpperCase() + string.slice(1)
}

export function upperOnlyFirstLetter(string: string) {
  return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase()
}

export function titleCase(string: string) {
  return string.split(' ').map((word) => upperOnlyFirstLetter(word)).join(' ')
}
