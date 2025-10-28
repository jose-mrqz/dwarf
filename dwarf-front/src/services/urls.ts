import { fetchApi } from "@/lib/api";

export type CreateUrlRequest = { url: string };
export type CreateUrlResponse = {
  id: string;
  url: string;
  code: string;
  created_at: string;
};
export type Url = { id: string; url: string; code: string; created_at: string };

export async function createUrl(req: CreateUrlRequest) {
  return fetchApi<CreateUrlResponse>("/urls", { method: "POST", body: req });
}

export async function getUrls() {
  return fetchApi<Url[]>("/urls");
}

export async function deleteUrl(id: string) {
  return fetchApi<void>(`/urls/${id}`, { method: "DELETE" });
}
