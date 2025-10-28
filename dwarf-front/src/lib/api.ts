export type HttpMethod = "GET" | "POST" | "PUT" | "PATCH" | "DELETE";

const BASE_API_URL =
  import.meta.env.VITE_DWARF_API_BASE_URL ?? "http://localhost:8000/api";
export const BASE_WEB_URL =
  import.meta.env.VITE_DWARF_WEB_BASE_URL ?? "http://localhost:8000";

export class ApiError extends Error {
  status: number;
  body: unknown;
  constructor(message: string, status: number, body: unknown) {
    const errorMessage =
      body && typeof body === "object" && "message" in body
        ? (body as { message: string }).message
        : message;
    super(errorMessage);
    this.status = status;
    this.body = body;
  }
}

export async function fetchApi<T>(
  path: string,
  opts: {
    method?: HttpMethod;
    body?: unknown;
    headers?: Record<string, string>;
  } = {},
): Promise<T> {
  const { method = "GET", body, headers } = opts;
  const res = await fetch(`${BASE_API_URL}${path}`, {
    method,
    headers: {
      "Content-Type": "application/json",
      Accept: "application/json",
      ...(headers ?? {}),
    },
    body: body ? JSON.stringify(body) : undefined,
  });
  const payload = await res.json().catch(() => null);
  if (!res.ok)
    throw new ApiError(
      payload?.message || "Request failed",
      res.status,
      payload,
    );
  if (res.status === 204) {
    return null as unknown as T;
  }
  return payload.data as T;
}
