export function slugify(value: string) {
  return value
      .toString()                     // Converter para string
      .normalize('NFD')               // Normalizar a string
      .replace(/[\u0300-\u036f]/g, '') // Remover acentos
      .toLowerCase()                  // Converter para minúsculas
      .trim()                         // Remover espaços em branco no início e no fim
      .replace(/[^a-z0-9\s-]/g, '')   // Remover caracteres especiais
      .replace(/[\s_-]+/g, '-')       // Substituir espaços e underlines por hífen
      .replace(/^-+|-+$/g, '');       // Remover hífens extras do início e do fim
}
