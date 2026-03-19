#!/usr/bin/env bash
set -euo pipefail

# Generates WordPress theme screenshot.png (1200x900) using configurable colors + logo.

THEME_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
THEME_JSON="${THEME_DIR}/theme.json"
OUTPUT_FILE="${THEME_DIR}/screenshot.png"

SHOW_HELP=false
LOGO_FILE=""
SITE_NAME=""
LOGO_TONE="light"
BG_TOP=""
BG_BOTTOM=""
TEXT_COLOR=""
ACCENT_COLOR=""

usage() {
  cat <<'EOF'
Usage: ./generate_theme_screenshot.sh [options]

Options:
  --logo PATH            Path to logo image file.
  --output PATH          Output PNG path (default: ./screenshot.png).
  --site-name TEXT       Footer label (default: current folder name).
  --logo-tone TONE       Expected logo tone: light|dark (default: light).
  --bg-top COLOR         Top background color.
  --bg-bottom COLOR      Bottom background color.
  --text-color COLOR     Foreground text color.
  --accent-color COLOR   Accent/line color.
  --help                 Show this help.

Examples:
  ./generate_theme_screenshot.sh
  ./generate_theme_screenshot.sh --logo-tone dark --site-name "Starbox"
  ./generate_theme_screenshot.sh --logo ./img/alt-logo.png --bg-top '#fafafa' --bg-bottom '#eaeaea' --text-color '#111'
EOF
}

require_value() {
  local opt="$1"
  local val="${2:-}"
  if [[ -z "${val}" || "${val}" == --* ]]; then
    echo "Missing value for ${opt}" >&2
    usage
    exit 1
  fi
}

while [[ $# -gt 0 ]]; do
  case "$1" in
    --logo)
      require_value "$1" "${2:-}"
      LOGO_FILE="${2:-}"
      shift 2
      ;;
    --output)
      require_value "$1" "${2:-}"
      OUTPUT_FILE="${2:-}"
      shift 2
      ;;
    --site-name)
      require_value "$1" "${2:-}"
      SITE_NAME="${2:-}"
      shift 2
      ;;
    --logo-tone)
      require_value "$1" "${2:-}"
      LOGO_TONE="${2:-}"
      shift 2
      ;;
    --bg-top)
      require_value "$1" "${2:-}"
      BG_TOP="${2:-}"
      shift 2
      ;;
    --bg-bottom)
      require_value "$1" "${2:-}"
      BG_BOTTOM="${2:-}"
      shift 2
      ;;
    --text-color)
      require_value "$1" "${2:-}"
      TEXT_COLOR="${2:-}"
      shift 2
      ;;
    --accent-color)
      require_value "$1" "${2:-}"
      ACCENT_COLOR="${2:-}"
      shift 2
      ;;
    --help|-h)
      SHOW_HELP=true
      shift
      ;;
    *)
      echo "Unknown option: $1" >&2
      usage
      exit 1
      ;;
  esac
done

if [[ "${SHOW_HELP}" == "true" ]]; then
  usage
  exit 0
fi

if [[ "${LOGO_TONE}" != "light" && "${LOGO_TONE}" != "dark" ]]; then
  echo "Invalid --logo-tone '${LOGO_TONE}'. Expected 'light' or 'dark'." >&2
  exit 1
fi

if [[ ! -f "${THEME_JSON}" ]]; then
  echo "Could not find theme.json at ${THEME_JSON}" >&2
  exit 1
fi

find_logo() {
  local candidates=(
    "${THEME_DIR}/img/starbox-logo.webp"
    "${THEME_DIR}/img/starbox-logo.png"
    "${THEME_DIR}/img/logo.webp"
    "${THEME_DIR}/img/logo.png"
  )

  local file
  for file in "${candidates[@]}"; do
    if [[ -f "${file}" ]]; then
      echo "${file}"
      return 0
    fi
  done

  return 1
}

if [[ -z "${LOGO_FILE}" ]]; then
  LOGO_FILE="$(find_logo || true)"
fi

if [[ -z "${LOGO_FILE}" ]]; then
  echo "No logo file found in img/. Expected one of: starbox-logo.webp, starbox-logo.png, logo.webp, logo.png" >&2
  exit 1
fi

if [[ "${LOGO_FILE}" != /* ]]; then
  LOGO_FILE="${THEME_DIR}/${LOGO_FILE}"
fi

if [[ ! -f "${LOGO_FILE}" ]]; then
  echo "Logo file does not exist: ${LOGO_FILE}" >&2
  exit 1
fi

if [[ -z "${SITE_NAME}" ]]; then
  SITE_NAME="$(basename "${THEME_DIR}")"
fi

palette_color() {
  local slug="$1"
  local fallback="$2"

  php -r '
    $file = $argv[1];
    $slug = $argv[2];
    $fallback = $argv[3];
    $json = json_decode(file_get_contents($file), true);
    if (!is_array($json)) { echo $fallback; exit(0); }
    $palette = $json["settings"]["color"]["palette"] ?? [];
    foreach ($palette as $entry) {
      if (($entry["slug"] ?? "") === $slug && !empty($entry["color"])) {
        echo $entry["color"];
        exit(0);
      }
    }
    echo $fallback;
  ' "${THEME_JSON}" "${slug}" "${fallback}"
}

if [[ -z "${BG_TOP}" ]]; then
  if [[ "${LOGO_TONE}" == "dark" ]]; then
    BG_TOP="#fafafa"
  else
    BG_TOP="$(palette_color "star-blue" "#101724")"
  fi
fi

if [[ -z "${BG_BOTTOM}" ]]; then
  if [[ "${LOGO_TONE}" == "dark" ]]; then
    BG_BOTTOM="#e9edf4"
  else
    BG_BOTTOM="$(palette_color "dark-blue" "#06142b")"
  fi
fi

if [[ -z "${TEXT_COLOR}" ]]; then
  if [[ "${LOGO_TONE}" == "dark" ]]; then
    TEXT_COLOR="#111111"
  else
    TEXT_COLOR="$(palette_color "light-regular" "#e6e6e6")"
  fi
fi

if [[ -z "${ACCENT_COLOR}" ]]; then
  ACCENT_COLOR="$(palette_color "highlight" "#41fcda")"
fi

BROWSER_BIN=""
for candidate in chromium-browser chromium google-chrome google-chrome-stable chrome; do
  if command -v "${candidate}" >/dev/null 2>&1; then
    BROWSER_BIN="${candidate}"
    break
  fi
done

if [[ -z "${BROWSER_BIN}" ]]; then
  echo "No headless Chromium/Chrome binary found (tried chromium-browser, chromium, google-chrome, google-chrome-stable, chrome)." >&2
  exit 1
fi

TMP_DIR="$(mktemp -d)"
HTML_FILE="${TMP_DIR}/theme-screenshot.html"

cat > "${HTML_FILE}" <<HTML
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Theme Screenshot</title>
  <style>
    :root {
      --bg-top: ${BG_TOP};
      --bg-bottom: ${BG_BOTTOM};
      --text: ${TEXT_COLOR};
      --accent: ${ACCENT_COLOR};
    }

    * { box-sizing: border-box; }

    body {
      margin: 0;
      width: 1200px;
      height: 900px;
      overflow: hidden;
      font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
      color: var(--text);
      background:
        radial-gradient(circle at 15% 10%, color-mix(in srgb, var(--accent) 30%, transparent), transparent 42%),
        radial-gradient(circle at 90% 90%, color-mix(in srgb, var(--accent) 20%, transparent), transparent 36%),
        linear-gradient(145deg, var(--bg-top), var(--bg-bottom));
      position: relative;
    }

    .card {
      position: absolute;
      inset: 70px;
      border-radius: 24px;
      border: 1px solid color-mix(in srgb, var(--text) 18%, transparent);
      backdrop-filter: blur(2px);
      background: color-mix(in srgb, var(--bg-top) 80%, black);
      display: grid;
      grid-template-rows: 1fr auto;
      padding: 64px;
    }

    .logo-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .logo-wrap img {
      max-width: 72%;
      max-height: 360px;
      object-fit: contain;
      filter: drop-shadow(0 10px 25px rgba(0, 0, 0, 0.35));
    }

    .footer {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 24px;
      font-size: 28px;
      letter-spacing: 0.06em;
      text-transform: uppercase;
      opacity: 0.95;
    }

    .rule {
      flex: 1;
      height: 2px;
      background: linear-gradient(90deg, transparent, var(--accent), transparent);
    }

    .site-name {
      white-space: nowrap;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <main class="card">
    <section class="logo-wrap">
      <img src="file://${LOGO_FILE}" alt="Site logo" />
    </section>
    <footer class="footer" aria-hidden="true">
      <div class="rule"></div>
      <div class="site-name">${SITE_NAME}</div>
      <div class="rule"></div>
    </footer>
  </main>
</body>
</html>
HTML

"${BROWSER_BIN}" \
  --headless \
  --disable-gpu \
  --hide-scrollbars \
  --window-size=1200,900 \
  --screenshot="${OUTPUT_FILE}" \
  "file://${HTML_FILE}" >/dev/null 2>&1

rm -rf "${TMP_DIR}"

echo "Created ${OUTPUT_FILE} (1200x900)"
echo "Palette: bg-top=${BG_TOP}, bg-bottom=${BG_BOTTOM}, text=${TEXT_COLOR}, accent=${ACCENT_COLOR}, logo-tone=${LOGO_TONE}"
