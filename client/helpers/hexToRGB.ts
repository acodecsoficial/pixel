export function convertHexToRGB(hex: string) {
  // Remove o caractere '#' se presente
  hex = hex.replace(/^#/, '');

  // Verifica se o valor hexadecimal é válido
  if (!/^(?:[0-9a-fA-F]{3}){1,2}$/.test(hex)) {
    return null;
  }

  // Se o valor hexadecimal tem 3 caracteres, expande para 6 caracteres
  if (hex.length === 3) {
    hex = hex.split('').map(char => char + char).join('');
  }

  // Divide o valor hexadecimal em três partes (para R, G e B)
  const r = parseInt(hex.substring(0, 2), 16);
  const g = parseInt(hex.substring(2, 4), 16);
  const b = parseInt(hex.substring(4, 6), 16);

  return [r, g, b] as const;
}


export function convertRGBToHex(r: number, g: number, b: number): string {
  // Converte cada valor RGB para hexadecimal
  const hexR = r.toString(16).padStart(2, '0');
  const hexG = g.toString(16).padStart(2, '0');
  const hexB = b.toString(16).padStart(2, '0');

  // Junta os valores hexadecimais
  const hexValue = `#${hexR}${hexG}${hexB}`;

  return hexValue.toUpperCase(); // Retorna o valor hexadecimal em maiúsculas
}
