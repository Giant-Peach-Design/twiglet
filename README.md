# Twiglet - Twig Extensions for WordPress

A collection of Twig extensions that provide WordPress-specific functionality and image handling utilities.

## Installation

```bash
composer require giantpeach/twiglet
```

## Image Functions

### `image_tag()` - Responsive Images

Creates a responsive `<img>` tag with `srcset` and `sizes` attributes, similar to Liquid's `image_tag` filter.

#### Basic Usage

```twig
{# Simple responsive image #}
{{ image_tag(image.id) }}

{# With custom sizes attribute #}
{{ image_tag(image.id, '(max-width: 768px) 100vw, 50vw') }}

{# With custom widths and attributes #}
{{ image_tag(image.id, '100vw', [400, 800, 1200], {'class': 'hero-image', 'loading': 'eager'}) }}
```

#### Parameters

- `image` (int|array) - WordPress attachment ID or image array with `id` property
- `sizes` (string) - CSS media query for responsive sizing (default: `'100vw'`)
- `widths` (array) - Array of image widths for srcset (default: `[375, 750, 1100, 1500, 2200]`)
- `attributes` (array) - Additional HTML attributes

#### Generated HTML

```html
<img src="/img/image.jpg?w=1100" 
     srcset="/img/image.jpg?w=375 375w, /img/image.jpg?w=750 750w, ..." 
     sizes="(max-width: 768px) 100vw, 50vw" 
     alt="..." 
     loading="lazy" 
     decoding="async" 
     class="hero-image">
```

### `picture_tag()` - Art Direction

Creates a `<picture>` element for art direction, allowing different images for different viewports (e.g., different aspect ratios for mobile vs desktop).

#### Basic Usage

```twig
{# Different images for mobile (≤640px) and desktop #}
{{ picture_tag(mobile_image.id, desktop_image.id) }}

{# Custom breakpoint #}
{{ picture_tag(mobile_image.id, desktop_image.id, '768px') }}

{# Full control over widths and attributes #}
{{ picture_tag(
   mobile_image.id, 
   desktop_image.id, 
   '640px',
   [375, 750],                    # mobile widths
   [1100, 1500, 2200],           # desktop widths
   {'class': 'responsive-hero'}   # attributes
) }}
```

#### Parameters

- `mobileImage` (int|array) - WordPress attachment ID for mobile viewport
- `desktopImage` (int|array) - WordPress attachment ID for desktop viewport  
- `breakpoint` (string) - Media query breakpoint (default: `'640px'`)
- `mobileWidths` (array) - Widths for mobile srcset (default: `[375, 750]`)
- `desktopWidths` (array) - Widths for desktop srcset (default: `[1100, 1500, 2200]`)
- `attributes` (array) - Additional HTML attributes for the `<img>` tag

#### Generated HTML

```html
<picture>
  <source media="(max-width: 640px)" 
          srcset="/img/mobile.jpg?w=375 375w, /img/mobile.jpg?w=750 750w">
  <img src="/img/desktop.jpg?w=1500" 
       srcset="/img/desktop.jpg?w=1100 1100w, /img/desktop.jpg?w=1500 1500w, ..." 
       alt="..." 
       loading="lazy" 
       decoding="async" 
       class="responsive-hero">
</picture>
```

#### Use Cases

Perfect for:
- **Different aspect ratios**: 16:9 hero images on desktop, 4:3 on mobile
- **Different crop focus**: Show full product on desktop, close-up on mobile
- **Art direction**: Landscape images on desktop, portrait on mobile

### `image()` - Simple Image URL

Returns a single image URL for a specific size configuration.

```twig
{# Get image URL for a configured size #}
<img src="{{ image(image.id, 'hero') }}" alt="...">

{# Using as background image #}
<div style="background-image: url('{{ image(image.id, 'banner') }}')"></div>
```

## Legacy Image Filter

### `image` Filter

Process images with specific dimensions and options.

```twig
{# Basic usage #}
{{ image_url | image(800, 600) }}

{# With WebP format #}
{{ image_url | image(800, 600, true, true) }}
```

#### Parameters

- `width` (int) - Image width (default: 500)
- `height` (int) - Image height (default: 500) 
- `crop` (bool) - Enable cropping (default: true)
- `webp` (bool) - Output WebP format (default: false)

## Complete Examples

### Hero Section with Art Direction

```twig
{# Different hero images for mobile vs desktop #}
<section class="hero">
  {{ picture_tag(
     hero.mobile_image.id,
     hero.desktop_image.id, 
     '768px',
     [375, 750],           # Mobile: 375w, 750w (for retina)
     [1200, 1600, 2400],   # Desktop: multiple sizes
     {'class': 'hero__image'}
  ) }}
  
  <div class="hero__content">
    <h1>{{ hero.title }}</h1>
  </div>
</section>
```

### Product Gallery

```twig
{# Responsive product images #}
<div class="product-gallery">
  {% for product_image in product.gallery %}
    <figure class="product-gallery__item">
      {{ image_tag(
         product_image.id, 
         '(max-width: 640px) 50vw, (max-width: 1024px) 33vw, 25vw',
         [200, 400, 600, 800],
         {'class': 'product-gallery__image'}
      ) }}
    </figure>
  {% endfor %}
</div>
```

### Blog Post Featured Images

```twig
{# Card layouts with consistent sizing #}
<article class="post-card">
  {{ image_tag(
     post.featured_image.id,
     '(max-width: 640px) 100vw, 350px',
     [350, 700],  # Exact sizes needed
     {'class': 'post-card__image'}
  ) }}
  
  <div class="post-card__content">
    <h2>{{ post.title }}</h2>
    <p>{{ post.excerpt }}</p>
  </div>
</article>
```

## Requirements

- WordPress environment
- giantpeach/images package
- Twig templating engine

## Features

- **Automatic WebP generation**: Both functions generate WebP variants automatically
- **Lazy loading**: Enabled by default for performance  
- **Async decoding**: Improves page load performance
- **Alt text**: Automatically pulled from WordPress attachment metadata
- **SVG support**: Handles SVG files appropriately without processing
- **Twig-safe**: Returns `Twig\Markup` objects to prevent auto-escaping