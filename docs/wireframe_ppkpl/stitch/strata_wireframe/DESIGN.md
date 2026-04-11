# Design System Document: Geotechnical Precision & Structural Integrity

## 1. Overview & Creative North Star: "The Technical Brutalist"
This design system is engineered for the rigors of geotechnical analysis, where data density and structural clarity are paramount. The Creative North Star is **"The Technical Brutalist"**—a philosophy that rejects decorative "fluff" in favor of raw, intentional architecture. 

The system utilizes a 4:3 tablet aspect ratio, moving away from standard web-responsive layouts toward a fixed-module "cockpit" feel. By abandoning color entirely, we shift the user’s focus to **tonal depth, line weight, and haptic patterns**. We break the "template" look through a rigorous 0px radius scale (absolute sharp corners) and a hyper-intentional use of negative space that creates an editorial, high-end blueprint aesthetic.

## 2. Colors: Tonal Architecture
The palette is strictly achromatic. Without color to signal importance, hierarchy is established through **Luminance Contrast**.

### The "No-Line" Rule
Traditional 1px borders are largely prohibited for sectioning. Instead, boundaries are defined by "Surface Steps." For example, a widget (Surface Container Low) sits on the Dashboard Base (Surface) to create a clear but borderless distinction.

### Surface Hierarchy & Nesting
*   **Surface (`#f9f9f9`):** The canvas. Used for the primary background.
*   **Surface Container Low (`#f3f3f3`):** Primary widget backgrounds.
*   **Surface Container High (`#e8e8e8`):** Active states or nested data groups within a widget.
*   **Surface Container Highest (`#e2e2e2`):** Headers or "Pinned" modules requiring immediate attention.

### Signature Textures (The Pattern Rule)
Since we cannot use red for "Error" or green for "Stable," we use CSS-based SVG patterns:
*   **Critical Status:** 45-degree dense diagonal hatching using `primary` on `surface_container`.
*   **Stable Status:** Solid `surface_container_highest` fill.
*   **Warning/Caution:** Stippled (dotted) pattern using `secondary` tokens.

## 3. Typography: The Editorial Blueprint
We pair **Space Grotesk** (Display/Headlines) with **Inter** (Body/UI) to create a "Technical Manual" aesthetic.

*   **Display (Space Grotesk):** Set to `Bold` or `Medium`. Use these for high-level metrics (e.g., "45.2m" depth readings). The wide apertures of Space Grotesk ensure legibility even at high density.
*   **Body (Inter):** Set to `Regular` for metadata and `SemiBold` for labels. Inter’s tall x-height provides the professional "functional" feel required for geotechnical logs.
*   **Monospaced Emphasis:** While not in the primary scale, numerical data within widgets should lean into tabular lining (Space Grotesk supports this) to ensure columns of numbers align perfectly for rapid scanning.

## 4. Elevation & Depth: Tonal Layering
In a grayscale world, shadows are rarely used to indicate "elevation." Instead, we use **Negative and Positive Volume.**

*   **The Layering Principle:** Treat the UI like a physical core sample. To "lift" an element, do not add a shadow; instead, darken the background behind it or lighten the element itself against a `surface_dim` backdrop.
*   **Ambient Shadows:** Use only for floating modals or context menus. 
    *   *Values:* `0px 20px 40px rgba(0,0,0, 0.06)`. It must feel like an atmospheric occlusion, not a "drop shadow."
*   **The "Ghost Border" Fallback:** For complex data visualizations where tonal shifts are insufficient, use a 1px stroke of `outline_variant` (`#c6c6c6`) at 40% opacity. 
*   **Glassmorphism:** For the side navigation or overlaying tooltips, use `surface_container_lowest` with an 80% opacity and `backdrop-filter: blur(12px)`. This preserves the "Technical Brutalist" aesthetic while adding high-end digital polish.

## 5. Components: Structural Primitives

### Buttons (The Structural Block)
*   **Primary:** Solid `primary` (Black) background, `on_primary` (White) text. 0px border radius.
*   **Secondary:** `surface_container_highest` fill, `primary` text.
*   **Tertiary:** No fill, `primary` text, `outline` (1px) stroke.

### Data Visualization Containers (Widgets)
*   **Header:** `headline-sm` text on `surface_container_low`.
*   **Content:** No dividers. Use 24px internal padding (`Spacing-6`) to separate data groups.
*   **Status Indicators:** Use 16x16px squares with pattern fills (Hatched, Stippled, or Solid) instead of colored circles.

### Input Fields
*   **Default:** `surface_container_lowest` fill with a bottom-only 2px stroke of `outline`.
*   **Focus:** The bottom stroke expands to 4px `primary`.
*   **Error:** The field background shifts to `surface_container_high` with a thick `primary` dashed border.

### Chips (Categorical Markers)
*   Strictly rectangular. Use `secondary_fixed` for inactive and `primary` for active. Text must be `label-sm` in all caps to denote "Technical Tagging."

## 6. Do’s and Don’ts

### Do:
*   **Do** use bold typography to replace color-based hierarchy. If something is important, make it larger and heavier.
*   **Do** embrace the 4:3 aspect ratio by using a 12-column grid with generous 32px gutters.
*   **Do** use various shades of gray to distinguish between "live data" (High Contrast) and "metadata" (Low Contrast).

### Don’t:
*   **Don’t** use rounded corners. Every element must be 90-degree angles to reinforce the "structural" nature of the application.
*   **Don’t** use standard icons for status. Build custom, geometric symbols that align with geotechnical engineering symbols (borehole icons, soil strata patterns).
*   **Don’t** use dividers between list items. Use a 1-step tonal shift on hover or alternating `surface_container_low` and `surface` backgrounds (Zebra striping).

---
**Director’s Note:** This system succeeds when it feels like a high-end digital blueprint. Every pixel should feel load-bearing. If a design element doesn't serve a functional purpose in communicating data, remove it.