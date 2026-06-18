import _sfc_main$1 from "./ContentQuery-C2tgz5RD.js";
import { defineComponent, useSlots, h, useSSRContext } from "vue";
import "/app/docs-site/node_modules/ohash/dist/index.mjs";
import "./query-RT5V1MJ1.js";
import "/app/docs-site/node_modules/perfect-debounce/dist/index.mjs";
import "../server.mjs";
import "/app/docs-site/node_modules/ofetch/dist/node.mjs";
import "#internal/nuxt/paths";
import "/app/docs-site/node_modules/hookable/dist/index.mjs";
import "/app/docs-site/node_modules/unctx/dist/index.mjs";
import "/app/docs-site/node_modules/h3/dist/index.mjs";
import "vue-router";
import "/app/docs-site/node_modules/defu/dist/defu.mjs";
import "/app/docs-site/node_modules/ufo/dist/index.mjs";
import "/app/docs-site/node_modules/nuxt/node_modules/@unhead/vue/dist/index.mjs";
import "vue/server-renderer";
import "/app/docs-site/node_modules/klona/dist/index.mjs";
import "./preview-CxS_JB1H.js";
import "/app/docs-site/node_modules/nuxt/node_modules/cookie-es/dist/index.mjs";
import "/app/docs-site/node_modules/destr/dist/index.mjs";
import "/app/docs-site/node_modules/nuxt/node_modules/ohash/dist/index.mjs";
const emptyNode = (slot, data) => h("pre", null, JSON.stringify({ message: "You should use slots with <ContentList>", slot, data }, null, 2));
const ContentList = defineComponent({
  name: "ContentList",
  props: {
    /**
     * Query props
     */
    /**
     * The path of the content to load from content source.
     * @default '/'
     */
    path: {
      type: String,
      required: false,
      default: void 0
    },
    /**
     * A query builder params object to be passed to <ContentQuery /> component.
     */
    query: {
      type: Object,
      required: false,
      default: void 0
    }
  },
  /**
   * Content empty fallback
   * @slot empty
   */
  /**
   * Content not found fallback
   * @slot not-found
   */
  render(ctx) {
    const slots = useSlots();
    const { path, query } = ctx;
    const contentQueryProps = {
      ...query || {},
      path: path || query?.path || "/"
    };
    return h(
      _sfc_main$1,
      contentQueryProps,
      {
        // Default slot
        default: slots?.default ? ({ data, refresh, isPartial }) => slots.default({ list: data, refresh, isPartial, ...this.$attrs }) : (bindings) => emptyNode("default", bindings.data),
        // Empty slot
        empty: (bindings) => slots?.empty ? slots.empty(bindings) : emptyNode("default", bindings?.data),
        // Not Found slot
        "not-found": (bindings) => slots?.["not-found"] ? slots?.["not-found"]?.(bindings) : emptyNode("not-found", bindings?.data)
      }
    );
  }
});
const _sfc_main = ContentList;
const _sfc_setup = _sfc_main.setup;
_sfc_main.setup = (props, ctx) => {
  const ssrContext = useSSRContext();
  (ssrContext.modules || (ssrContext.modules = /* @__PURE__ */ new Set())).add("node_modules/@nuxt/content/dist/runtime/components/ContentList.vue");
  return _sfc_setup ? _sfc_setup(props, ctx) : void 0;
};
export {
  _sfc_main as default
};
//# sourceMappingURL=ContentList-CUraHxds.js.map
