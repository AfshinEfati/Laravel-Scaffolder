import { hash } from "/app/docs-site/node_modules/ohash/dist/index.mjs";
import { q as queryContent, w as withContentBase, e as encodeQueryParams, b as addPrerenderPath, s as shouldUseClientDB, j as jsonStringify } from "./query-RT5V1MJ1.js";
import { u as useContentPreview } from "./preview-CxS_JB1H.js";
import { b as useRuntimeConfig } from "../server.mjs";
const fetchContentNavigation = async (queryBuilder) => {
  const { content } = useRuntimeConfig().public;
  if (typeof queryBuilder?.params !== "function") {
    queryBuilder = queryContent(queryBuilder);
  }
  const params = queryBuilder.params();
  const apiPath = content.experimental.stripQueryParameters ? withContentBase(`/navigation/${`${hash(params)}.${content.integrity}`}/${encodeQueryParams(params)}.json`) : withContentBase(`/navigation/${hash(params)}.${content.integrity}.json`);
  {
    addPrerenderPath(apiPath);
  }
  if (shouldUseClientDB()) {
    const generateNavigation = await import("./client-db-CpazKEU0.js").then((m) => m.generateNavigation);
    return generateNavigation(params);
  }
  const data = await $fetch(apiPath, {
    method: "GET",
    responseType: "json",
    params: content.experimental.stripQueryParameters ? void 0 : {
      _params: jsonStringify(params),
      previewToken: useContentPreview().getPreviewToken()
    }
  });
  if (typeof data === "string" && data.startsWith("<!DOCTYPE html>")) {
    throw new Error("Not found");
  }
  return data;
};
export {
  fetchContentNavigation as f
};
//# sourceMappingURL=navigation-CNamej42.js.map
