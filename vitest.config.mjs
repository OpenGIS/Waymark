import { defineConfig } from "vitest/config";

export default defineConfig({
  test: {
    environment: "jsdom",
    setupFiles: ["waymark-js/tests/setup.js"],
    include: ["waymark-js/tests/**/*.test.js"],
  },
});
