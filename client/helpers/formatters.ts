import {
  formatGeneral,
  formatNumeral,
  NumeralThousandGroupStyles
} from 'cleave-zen'


export function formatCurrency(number: number) {
  return Intl.NumberFormat('pt-br', { style: 'currency', currency: 'BRL' }).format(number)
}

export function currencyMask(value: number) {
  return formatNumeral(value+'', {
    numeralThousandsGroupStyle: NumeralThousandGroupStyles.THOUSAND,
    numeralDecimalMark: ',',
    delimiter: '.',
    numeralPositiveOnly: true,
  })
}

export function cpfMask(value: string) {
  return formatGeneral(value, {
    delimiters: ['.', '.', '-'],
    blocks: [3, 3, 3, 2],
    numericOnly: true
  })
}

export function phoneMask(value: string) {
  return formatGeneral(value, {
    blocks: [0, 2, 5, 4],
    delimiters: ['(', ') ', '-'],
    numericOnly: true
  })
}
