```markdown
# Design System Specification: The Architectural Blueprint

## 1. Overview & Creative North Star

**Creative North Star: "The Pure Blueprint"**

This design system is a radical departure from the "polished prototype." It is a high-end, editorial interpretation of a structural wireframe—a "skeletal aesthetic" that celebrates the raw logic of an interface before it is dressed in skin. 

To move beyond a generic wireframe, we treat every layout as a piece of technical drafting. We break the "template" look through **intentional asymmetry**, where content blocks are offset to create a sense of movement, and **extreme contrast**, where heavy 2px strokes meet razor-thin guides. This is not a "placeholder"; it is a signature visual identity that communicates structural clarity, intentionality, and the beauty of a work-in-progress.

---

## 2. Colors

The palette is strictly achromatic, leveraging the Material Design tokens to define depth through tonal shifts rather than hues.

*   **Primary (#000000):** Reserved exclusively for structural strokes and "active" skeletal elements.
*   **Surface (#f9f9f9):** The primary canvas. It should feel like a fresh sheet of drafting paper.
*   **The "No-Line" Rule:** While this system is stroke-heavy, you must prohibit 1px solid borders for *sectioning*. Boundaries are defined by shifting from `surface` to `surface-container-low` (#f3f3f4). Use the `outline` token only for the "skeletal" frames of components.
*   **Surface Hierarchy & Nesting:** Create "stacked" logic. A `surface-container-lowest` (#ffffff) card should sit on a `surface-container` (#eeeeee) background. This creates a "sheet-on-sheet" feel that replaces traditional shadows.
*   **Signature Textures:** For high-priority areas, use a subtle diagonal hatch pattern (CSS-generated) using the `outline-variant` (#c6c6c6) to provide a "technical drawing" texture that flat fills cannot achieve.

---

## 3. Typography (The "Redacted" System)

In this system, typography is not meant to be read; it is meant to be felt as a structural weight. We use **Space Grotesk** for display and **Inter** for utility, but we "redact" the content.

*   **Display & Headline Scales:** Represented by thick, solid horizontal bars (`primary`). The length of the bar should be varied to mimic the rhythm of a real headline.
*   **Body & Label Scales:** Represented by "wavy" or "sinusoidal" lines using the `outline` token. 
*   **Logic:**
    *   **Display-LG (3.5rem):** A single, heavy 8px height bar.
    *   **Body-MD (0.875rem):** A 2px height wavy line.
*   **The Hierarchy:** Use the typography scale to dictate the *thickness* and *spacing* of these lines. A `title-lg` bar is thicker and has more leading than a `body-sm` wavy line. This preserves the visual "gray value" of a real layout.

---

## 4. Elevation & Depth

We reject drop shadows. Depth is communicated through **Tonal Layering** and **Line Weight**.

*   **The Layering Principle:** To lift an element, change its background to `surface-container-lowest` (#ffffff) and give it a 2px `primary` stroke. To recede an element, remove the stroke and use `surface-dim` (#dadada).
*   **The "Ghost Border" Fallback:** For secondary structural elements, use the `outline-variant` (#c6c6c6) at 20% opacity. This creates a "pencil guide" effect that feels like an architect's initial sketch.
*   **Glassmorphism & Depth:** For modals or floating menus, use a `surface` fill with 80% opacity and a heavy `backdrop-filter: blur(10px)`. This allows the "structural lines" underneath to peek through, maintaining the blueprint theme.

---

## 5. Components

All components are strictly 0px border-radius (`none`).

### Buttons
*   **Primary:** A solid black (`primary`) box. Inside, a single white (`on-primary`) straight line represents the label.
*   **Secondary:** A 2px black outline box with a white background. Inside, a single black straight line.
*   **Tertiary:** No box. A single black straight line with a 2px underline (using `outline-variant`).

### Input Fields
*   **Default:** A 1px `outline` box. Inside, a very faint wavy line (`outline-variant`) representing placeholder text.
*   **Focus:** Increase stroke to 2px `primary`.

### Cards & Lists
*   **Forbid Dividers:** Do not use lines to separate list items. Instead, use a 4px vertical gap (Spacing Scale) or alternate background colors between `surface` and `surface-container-low`.
*   **Image Placeholders:** Represented by a box with a large "X" stretching from corner to corner using 1px `outline-variant`.

### Skeleton Chips
*   Small, rectangular boxes with a 1px `outline`. Inside, a single short, straight line. Used for tags or categories.

---

## 6. Do's and Don'ts

### Do:
*   **Do** embrace white space. Treat the layout like a gallery wall; items should feel intentionally placed, not crammed.
*   **Do** use varying line weights. Use a 3px stroke for the main container and a 0.5px stroke for internal details to create "Visual Pathing."
*   **Do** ensure all "redacted text" lines align to the baseline grid of the typography scale.

### Don't:
*   **Don't** use icons. If an icon is needed, represent it with a small square containing a diagonal slash.
*   **Don't** use any rounded corners. Everything must be 90-degree angles to maintain the "structural blueprint" aesthetic.
*   **Don't** use real text, even for "Lorem Ipsum." Real text breaks the immersion of the low-fidelity blueprint. Use the wavy/straight line system exclusively.
*   **Don't** use standard shadows. If an element must "float," offset a solid `surface-container-highest` (#e2e2e2) box 4px behind it to act as a "hard shadow."

---

## 7. Interaction Patterns

*   **Hover States:** When hovering over a component, fill the container with a `primary_container` (#3b3b3b) and flip the internal lines to `on_primary`. 
*   **Active/Selected:** Add a "hatching" pattern (diagonal lines) to the background of the element to indicate it is currently active.
*   **Error States:** Use the `error` (#ba1a1a) token sparingly. An error is represented by a 2px red stroke replacing the black stroke, and the wavy lines inside turning red.