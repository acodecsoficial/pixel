export function isValidPhone(phone: string): boolean {
  phone = phone.replace(/[^\d]+/g, ''); // Remove caracteres não numéricos

  // Verifica o tamanho do número
  if (phone.length !== 10 && phone.length !== 11) return false;

  // Verifica o DDD (primeiros dois dígitos)
  const ddd = parseInt(phone.substring(0, 2));
  if (ddd < 0 || ddd > 99) return false;

  return true;
}


export function isValidCPF(cpf: string): boolean {
  cpf = cpf.replace(/[^\d]+/g, ''); // Remove caracteres não numéricos

  if (cpf.length !== 11) return false;

  // Verifica se todos os dígitos são iguais
  if (/^(\d)\1+$/.test(cpf)) return false;

  // Validação do primeiro dígito verificador
  let soma = 0;
  for (let i = 0; i < 9; i++) {
      soma += parseInt(cpf.charAt(i)) * (10 - i);
  }
  let resto = 11 - (soma % 11);
  let digito1 = resto < 10 ? resto : 0;
  if (digito1 !== parseInt(cpf.charAt(9))) return false;

  // Validação do segundo dígito verificador
  soma = 0;
  for (let i = 0; i < 10; i++) {
      soma += parseInt(cpf.charAt(i)) * (11 - i);
  }
  resto = 11 - (soma % 11);
  let digito2 = resto < 10 ? resto : 0;
  if (digito2 !== parseInt(cpf.charAt(10))) return false;

  return true;
}
