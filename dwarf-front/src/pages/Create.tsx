import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { createUrl } from "@/services/urls";
import { useState } from "react";
import { Alert, AlertTitle, AlertDescription } from "@/components/ui/alert";
import { CheckCircle2Icon, XCircleIcon } from "lucide-react";
import { ApiError } from "@/lib/api";

export default function Create() {
  const [url, setUrl] = useState("");
  const [showSuccess, setShowSuccess] = useState(false);
  const [showError, setShowError] = useState(false);
  const [errorMessage, setErrorMessage] = useState("");

  async function handleSubmit(e: React.FormEvent) {
    e.preventDefault();
    setShowSuccess(false);
    setShowError(false);

    try {
      await createUrl({ url: url });
      setShowSuccess(true);
      setUrl("");
    } catch (error) {
      setShowError(true);
      setErrorMessage(
        error instanceof ApiError
          ? error.message
          : "Failed to create URL. Please try again.",
      );
    }
  }

  return (
    <section className="mx-auto p-4">
      <form className="flex flex-col gap-3" onSubmit={handleSubmit}>
        <div className="grid gap-2">
          <Label htmlFor="url">URL</Label>
          <Input
            id="url"
            type="url"
            inputMode="url"
            placeholder="https://example.com/a-very-long-url"
            value={url}
            onChange={(e) => {
              setUrl(e.target.value);
            }}
            required
          />
        </div>
        <Button type="submit" className="w-full sm:w-auto">
          Crear
        </Button>
      </form>
      {showSuccess && (
        <Alert className="mt-4 text-green-600">
          <CheckCircle2Icon />
          <AlertTitle className="text-left">
            URL created successfully
          </AlertTitle>
          <AlertDescription>
            Your URL has been created successfully. You can create another one or
            go to the home page.
          </AlertDescription>
        </Alert>
      )}

      {showError && (
        <Alert className="mt-4 text-red-600" variant="destructive">
          <XCircleIcon />
          <AlertTitle className="text-left">Error creating URL</AlertTitle>
          <AlertDescription>{errorMessage}</AlertDescription>
        </Alert>
      )}
    </section>
  );
}
