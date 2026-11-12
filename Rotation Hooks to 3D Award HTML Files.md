# Rotation Hooks to 3D Award HTML Files

This document explains the mandatory changes required in every 3D award HTML file to make it compatible with the rotation system driven by `Recognition.js`.

---

## Purpose

Every 3D award HTML file (loaded inside the recognition modal) must expose rotation hooks so that `Recognition.js` can control the model's rotation on scroll. Without these hooks, the award will not rotate.

---

## Required Changes in the HTML File

Open the 3D award HTML file and locate the **end of the render/animation loop setup** — specifically where the main model group variable is defined (the variable that holds the loaded GLTF/OBJ scene, e.g. `grp`, `trophy`, `model`, etc.).

**Immediately after** that group is defined (and inside the same script scope), add the following three hooks. Replace `GROUP_VAR` with the actual variable name of your model group.

### For classic `<script>` files:

```js
window.cwtAwardGroup = GROUP_VAR;
window.cwtAwardRotate = function (angle) { GROUP_VAR.rotation.y = angle; };
window.addEventListener('message', function (e) {
  if (e.data && e.data.type === 'cwt-award-rotate') {
    GROUP_VAR.rotation.y = e.data.angle;
  }
});
```

### For `<script type="module">` files:

```js
window.cwtAwardGroup = GROUP_VAR;
window.cwtAwardRotate = function (angle) { GROUP_VAR.rotation.y = angle; };
window.addEventListener('message', function (e) {
  if (e.data && e.data.type === 'cwt-award-rotate') {
    GROUP_VAR.rotation.y = e.data.angle;
  }
});
```

> ⚠️ **Important for module scripts:** You must explicitly assign to `window.cwtAwardGroup` (not just declare a variable). Module-scope variables are not accessible from outside, so the `window.` prefix is mandatory.

---

## Checklist for Each New 3D HTML File

- [ ] Identify the **main model group variable name** (e.g. `grp`, `trophy`, `model`).
- [ ] Add the three hooks shown above, using that variable name.
- [ ] Place the hooks **after** the group is created and the model is loaded, but **before** the render loop ends (anywhere in the same scope works).
- [ ] For module scripts, ensure `window.cwtAwardGroup` is set explicitly.
- [ ] Verify `Recognition.js` can read `win.cwtAwardGroup` (it checks `window.cwtAwardGroup` first, then falls back to `getScene(win)` which does not work for module scripts).

---

## What Each Hook Does

| Hook | Purpose |
|---|---|
| `window.cwtAwardGroup = GROUP_VAR` | Exposes the model group so the parent page can find and manipulate it. |
| `window.cwtAwardRotate = function(angle)` | Function the parent calls to set the Y rotation directly. |
| `window.addEventListener('message', ...)` | Fallback cross-frame messaging hook — sets Y rotation when a `cwt-award-rotate` message is received with an `angle` value. |

---

## Do Not

- Do **not** rename the model group variable in the hooks — they must reference the actual variable used in the render loop.
- Do **not** place the hooks before the group is created (will throw `undefined`).
- Do **not** omit the `window.` prefix in module scripts — the hooks will silently fail otherwise.

---

## Reference Example (3rd Award — Microsoft Glass Trophy)

File: `cwt3d_6af471f4460f07.02072749.html`
Group variable: `trophy`
Script type: `<script type="module">`

```js
window.cwtAwardGroup = trophy;
window.cwtAwardRotate = function (angle) { trophy.rotation.y = angle; };
window.addEventListener('message', function (e) {
  if (e.data && e.data.type === 'cwt-award-rotate') {
    trophy.rotation.y = e.data.angle;
  }
});
```

---

## AI Prompt — Auto-Add Rotation Hooks to Any 3D Award HTML File

Use the prompt below with any AI coding assistant (ChatGPT, Claude, Copilot, etc.) to automatically apply the rotation hooks to a new 3D award HTML file. Just paste the prompt and attach / paste the HTML file content.

````text
You are editing a 3D award HTML file used inside a recognition modal. The parent page
(Recognition.js) controls this model's Y rotation on scroll, but only if the HTML file
exposes specific rotation hooks on the `window` object.

Your task: modify the HTML file to add the required rotation hooks. Follow these rules exactly.

STEP 1 — Detect the script type
- Look for `<script type="module">` vs `<script>`.
- Note: for module scripts, `window.` prefix is MANDATORY (module scope is isolated).

STEP 2 — Find the main model group variable
- Search the file for the variable that holds the loaded 3D model (e.g. `grp`, `trophy`,
  `model`, `group`, `root`). It is usually the object passed to `scene.add(...)` or the
  parent of all loaded meshes.
- If multiple candidates exist, choose the one that represents the whole award model
  (the one whose `.rotation.y` would visually rotate the entire award).

STEP 3 — Insert the hooks
Insert the following code block IMMEDIATELY AFTER the group variable is created and
the model is loaded (i.e. after `scene.add(GROUP_VAR)` or after the loader callback
assigns the group). Do NOT place it before the group exists.

Replace `GROUP_VAR` with the detected variable name. Keep the code otherwise identical:

    window.cwtAwardGroup = GROUP_VAR;
    window.cwtAwardRotate = function (angle) { GROUP_VAR.rotation.y = angle; };
    window.addEventListener('message', function (e) {
      if (e.data && e.data.type === 'cwt-award-rotate') {
        GROUP_VAR.rotation.y = e.data.angle;
      }
    });

STEP 4 — Validate
- The three hooks must be in the SAME scope as GROUP_VAR.
- `window.cwtAwardGroup` must be explicitly assigned (not just a local var).
- Do NOT rename GROUP_VAR — it must match the actual variable used in the render loop.
- Do NOT modify the render loop, camera, lights, or model loading logic.
- Do NOT add duplicate hooks if they already exist — check first.

OUTPUT FORMAT
- Return ONLY the modified HTML file (or the exact edited script block with clear
  before/after context).
- At the end, briefly state: detected script type, detected group variable name,
  and line number where the hooks were inserted.

Here is the HTML file content:
<paste the full HTML file content here>
````

---

## Quick Copy-Paste Snippet (for manual use)

If you prefer not to use AI, copy this snippet and change `GROUP_VAR` manually:

```js
window.cwtAwardGroup = GROUP_VAR;
window.cwtAwardRotate = function (angle) { GROUP_VAR.rotation.y = angle; };
window.addEventListener('message', function (e) {
  if (e.data && e.data.type === 'cwt-award-rotate') {
    GROUP_VAR.rotation.y = e.data.angle;
  }
});
```
