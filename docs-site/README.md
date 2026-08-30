# Laravel Scaffolder Documentation Site

Documentation website for the Laravel Scaffolder package.

## Quick Start

```bash
# Install dependencies exactly from package-lock.json
npm ci --legacy-peer-deps

# Development server
npm run dev

# Build for production
npm run build

# Generate static site
npm run generate

# Preview production build
npm run preview
```

## Project Structure

```text
docs-site/
├── assets/          # CSS and fonts
├── components/      # Vue components
├── content/         # Markdown documentation
│   ├── en/          # English docs
│   └── fa/          # Persian docs
├── layouts/         # Page layouts
├── pages/           # Route pages
├── public/          # Static assets
└── nuxt.config.ts   # Nuxt configuration
```

## Creating the OG Image

See [HOW_TO_GENERATE_OG_IMAGE.md](./HOW_TO_GENERATE_OG_IMAGE.md) for instructions.

## Deployment

GitHub Actions builds and deploys the site to GitHub Pages whenever `main` is updated.

Local production build:

```bash
npm run generate
```

Generated output is written to `.output/public/`.

The published site is available at `https://afshinefati.github.io/Laravel-Scaffolder/`.

## Adding Content

- English documentation: `content/en/`
- Persian documentation: `content/fa/`

Code blocks support syntax highlighting and copy controls through the documentation UI.

## Technologies

- Nuxt 3
- Nuxt Content
- Tailwind CSS
- Tailwind Typography

## Contributing

1. Edit content in `content/en/` or `content/fa/`.
2. Run `npm run dev` for local review.
3. Run `npm run generate` before submitting structural documentation changes.
4. Push changes and let GitHub Actions deploy the site.

## License

Same MIT license as the main Laravel Scaffolder package.
