declare global {
  interface Window {
    turnstile?: Turnstile.Turnstile
  }
  function fbq(type: 'track', event_name: string, data?: Record<string, any>): void;
}

export interface HomeData {
  categories?: any[],
  top_games?: any[],
  top_winners?: Winner[],
}

interface Winner {
  name: string;
  amount: number;
  game_name: string;
  game_image: string;
}

export interface DepositData {
  amount: number;
  document: string;
  coupon: string;
  accept_bonus: boolean;
}

export interface CasinoGame {
  id: number;
  provider_id: number;
  reference: string;
  name: string;
  provider_name: string;
  clicks: number;
  platform: string;
  active: number;
  image: string;
  casino_game_type_id: number;
  created_at: string;
  updated_at: string;
  slug_url: string;
  gameURL: string
}

export interface Provider {
  id: number;
  slug: string;
  name: string;
  aggregator: string;
  image: string;
  order: number;
  created_at: string | null;
  updated_at: string | null;
}

export interface Banner {
  id: number
  action: string
  image: string
  order_value: number
  created_at: any
  updated_at: any
  order: number
}

export interface Theme {
  name: string
  thumb: string
  components: Component[]
}

export interface Component {
  name: string
  sample: string
  fields: Field[]
}

export interface Field {
  title: string
  name: string
  value: string
}

export interface Social {
  instagram: string
  whatsapp: string
  telegram: string
  youtube: string
}

export interface DesktopActionBtn {
  image: string
  action: string
}

export interface MobileActionBtn {
  image: string
  action: string
}

export interface FooterEmails {
  LEGAL_EMAIL: string
  PARTNER_EMAIL: string
  SUPPORT_EMAIL: string
}

export interface Colors {
  THEME_COLORS_DEFAULT_PRIMARY: string
  THEME_COLORS_DEFAULT_BG_PRIMARY: string
  THEME_COLORS_DEFAULT_SECONDARY: string
  THEME_COLORS_DEFAULT_BG_SECONDARY: string
  THEME_COLORS_DEFAULT_TEXTS: string
  THEME_COLORS_DEFAULT_LINKS: string
  THEME_COLORS_DEFAULT_TITLES: string
  THEME_COLORS_DEFAULT_BUTTON_BG: string
  THEME_COLORS_DEFAULT_BUTTON_TEXT: string
  THEME_COLORS_DEFAULT_SUCCESS: string
  THEME_COLORS_DEFAULT_ERROR: string
  THEME_COLORS_DEFAULT_WARNING: string
  THEME_COLORS_DEFAULT_INFO: string
  THEME_COLORS_GAME_TITLE: string
  THEME_COLORS_GAME_SUBTITLE: string
  THEME_COLORS_GAME_BUTTON_BG: string
  THEME_COLORS_GAME_BUTTON_TEXT: string
  THEME_COLORS_GAME_OVERLAY: string
  THEME_COLORS_GAME_ICON: string
  THEME_COLORS_HEADER_BG: string
  THEME_COLORS_HEADER_LINKS: string
  THEME_COLORS_HEADER_TEXTS: string
  THEME_COLORS_HEADER_REGISTER_BG: string
  THEME_COLORS_HEADER_REGISTER_TEXT: string
  THEME_COLORS_HEADER_LOGIN_BG: string
  THEME_COLORS_HEADER_LOGIN_TEXT: string
  THEME_COLORS_SIDEBAR_BG: string
  THEME_COLORS_SIDEBAR_LINKS: string
  THEME_COLORS_SIDEBAR_TITLES: string
  THEME_COLORS_SIDEBAR_BUTTON_BG: string
  THEME_COLORS_SIDEBAR_BUTTON_TEXT: string
  THEME_COLORS_SIDEBAR_CTA_BG: string
  THEME_COLORS_SIDEBAR_CTA_TEXT: string
  THEME_COLORS_FOOTER_BG_PRIMARY: string
  THEME_COLORS_FOOTER_BG_SECONDARY: string
  THEME_COLORS_FOOTER_LINKS: string
  THEME_COLORS_FOOTER_TITLES: string
  THEME_COLORS_FOOTER_TEXTS: string
  THEME_COLORS_FOOTER_BUTTON_BG: string
  THEME_COLORS_FOOTER_BUTTON_TEXT: string
  THEME_COLORS_AUTH_BG_PRIMARY: string
  THEME_COLORS_AUTH_BG_INPUTS: string
  THEME_COLORS_AUTH_TEXT_INPUTS: string
  THEME_COLORS_AUTH_BG_CLOSE: string
  THEME_COLORS_AUTH_ICON_CLOSE: string
  THEME_COLORS_AUTH_LINKS: string
  THEME_COLORS_AUTH_TITLES: string
  THEME_COLORS_AUTH_TEXTS: string
  THEME_COLORS_AUTH_BUTTON_BG: string
  THEME_COLORS_AUTH_BUTTON_TEXT: string
  THEME_COLORS_AUTH_BACKDROP: string
}
