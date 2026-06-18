import { _ as __nuxt_component_0 } from "./nuxt-link-YJU-IzoS.js";
import { defineComponent, useSlots, toRefs, computed, h, useSSRContext } from "vue";
import { hash } from "/app/docs-site/node_modules/ohash/dist/index.mjs";
import { a as useContentDisabled, u as useAsyncData } from "./query-RT5V1MJ1.js";
import { f as fetchContentNavigation } from "./navigation-CNamej42.js";
import { c as useState } from "../server.mjs";
import "/app/docs-site/node_modules/ufo/dist/index.mjs";
import "/app/docs-site/node_modules/defu/dist/defu.mjs";
import "/app/docs-site/node_modules/perfect-debounce/dist/index.mjs";
import "/app/docs-site/node_modules/hookable/dist/index.mjs";
import "/app/docs-site/node_modules/klona/dist/index.mjs";
import "./preview-CxS_JB1H.js";
import "/app/docs-site/node_modules/nuxt/node_modules/cookie-es/dist/index.mjs";
import "/app/docs-site/node_modules/h3/dist/index.mjs";
import "/app/docs-site/node_modules/destr/dist/index.mjs";
import "/app/docs-site/node_modules/nuxt/node_modules/ohash/dist/index.mjs";
import "/app/docs-site/node_modules/ofetch/dist/node.mjs";
import "#internal/nuxt/paths";
import "/app/docs-site/node_modules/unctx/dist/index.mjs";
import "vue-router";
import "/app/docs-site/node_modules/nuxt/node_modules/@unhead/vue/dist/index.mjs";
import "vue/server-renderer";
const ContentNavigation = defineComponent({
  name: "ContentNavigation",
  props: {
    /**
     * A query to be passed to `fetchContentNavigation()`.
     */
    query: {
      type: Object,
      required: false,
      default: void 0
    }
  },
  async setup(props) {
    const {
      query
    } = toRefs(props);
    const queryBuilder = computed(() => {
      if (typeof query.value?.params === "function") {
        return query.value.params();
      }
      return query.value;
    });
    if (!queryBuilder.value && useState("dd-navigation").value) {
      const { navigation: navigation2 } = useContentDisabled();
      return { navigation: navigation2 };
    }
    const { data: navigation } = await useAsyncData(
      `content-navigation-${hash(queryBuilder.value)}`,
      () => fetchContentNavigation(queryBuilder.value)
    );
    return { navigation };
  },
  /**
   * Navigation empty fallback
   * @slot empty
   */
  render(ctx) {
    const slots = useSlots();
    const { navigation } = ctx;
    const renderLink = (link) => h(__nuxt_component_0, { to: link._path }, () => link.title);
    const renderLinks = (data, level) => h(
      "ul",
      level ? { "data-level": level } : null,
      data.map((link) => {
        if (link.children) {
          return h("li", null, [renderLink(link), renderLinks(link.children, level + 1)]);
        }
        return h("li", null, renderLink(link));
      })
    );
    const defaultNode = (data) => renderLinks(data, 0);
    return slots?.default ? slots.default({ navigation, ...this.$attrs }) : defaultNode(navigation);
  }
});
const _sfc_main = ContentNavigation;
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("node_modules/@nuxt/content/dist/runtime/components/ContentNavigation.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
//# sourceMappingURL=ContentNavigation-DVkjYk-k.js.map
