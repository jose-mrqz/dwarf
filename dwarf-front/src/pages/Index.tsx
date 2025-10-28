import { useState, useEffect } from "react";
import { getUrls, deleteUrl, type Url } from "@/services/urls";
import {
  Table,
  TableCaption,
  TableHeader,
  TableBody,
  TableRow,
  TableHead,
  TableCell,
} from "@/components/ui/table";
import { TypographyInlineCode } from "@/components/ui/typography-inline-code";
import { Button } from "@/components/ui/button";
import { ArrowRightIcon, TrashIcon } from "lucide-react";
import { ButtonGroup } from "@/components/ui/button-group";
import { BASE_WEB_URL } from "@/lib/api";
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
  AlertDialogTrigger,
} from "@/components/ui/alert-dialog";

export default function Index() {
  const [urls, setUrls] = useState<Url[]>([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);
  const [deletingId, setDeletingId] = useState<string | null>(null);
  const [openDialogId, setOpenDialogId] = useState<string | null>(null);

  useEffect(() => {
    const fetchUrls = async () => {
      try {
        setLoading(true);
        setError(null);
        const data = await getUrls();
        setUrls(data);
      } catch (err) {
        setError(err instanceof Error ? err.message : "Failed to fetch URLs");
      } finally {
        setLoading(false);
      }
    };

    fetchUrls();
  }, []);

  const handleDelete = async (id: string) => {
    try {
      console.log("Deleting URL with ID:", id);
      setDeletingId(id);
      setOpenDialogId(null);
      await deleteUrl(id);
      setUrls(urls.filter(url => url.id !== id));
    } catch (error) {
      setError(error instanceof Error ? error.message : "Failed to delete URL");
    } finally {
      setDeletingId(null);
    }
  };

  if (loading) {
    return (
      <div className="container mx-auto p-4">
        <h2 className="mb-4 text-2xl font-bold">URLs</h2>
        <div className="text-center">Loading...</div>
      </div>
    );
  }

  if (error) {
    return (
      <div className="container mx-auto p-4">
        <h2 className="mb-4 text-2xl font-bold">URLs</h2>
        <div className="text-red-500">Error: {error}</div>
      </div>
    );
  }

  return (
    <div className="container mx-auto p-4">
      {urls.length === 0 ? (
        <h2 className="text-center text-gray-500">No URLs created yet.</h2>
      ) : (
        <Table>
          <TableCaption>Last generated URLs.</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead className="w-[100px]">#</TableHead>
              <TableHead>URL</TableHead>
              <TableHead>Code</TableHead>
              <TableHead className="text-right">Created At</TableHead>
              <TableHead className="text-right">Actions</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {urls.map((url) => (
              <TableRow key={url.id}>
                <TableCell className="text-left font-medium">
                  {url.id}
                </TableCell>
                <TableCell className="text-left">
                  <a
                    href={url.url}
                    className="text-blue-500 hover:text-blue-600 hover:underline"
                    target="_blank"
                    rel="noopener noreferrer"
                  >
                    {url.url}
                  </a>
                </TableCell>
                <TableCell className="text-muted-foreground text-left">
                  <TypographyInlineCode>{url.code}</TypographyInlineCode>
                </TableCell>
                <TableCell className="text-right">
                  {new Intl.DateTimeFormat("es-ES", {
                    dateStyle: "short",
                    timeStyle: "short",
                  }).format(new Date(url.created_at))}
                </TableCell>
                <TableCell className="flex justify-end">
                  <ButtonGroup>
                    <Button variant="outline">
                      <a
                        href={`${BASE_WEB_URL}/urls/${url.code}/redirect`}
                        target="_blank"
                        className="flex items-center gap-2"
                        rel="noopener noreferrer"
                      >
                        <ArrowRightIcon className="h-4 w-4" />
                        Go to URL
                      </a>
                    </Button>
                    <AlertDialog 
                      open={openDialogId === url.id} 
                      onOpenChange={(open) => setOpenDialogId(open ? url.id : null)}
                    >
                      <AlertDialogTrigger asChild>
                        <Button 
                          variant="outline" 
                          disabled={deletingId === url.id}
                        >
                          <TrashIcon className="h-4 w-4" />
                          {deletingId === url.id ? "Deleting..." : "Delete"}
                        </Button>
                      </AlertDialogTrigger>
                      <AlertDialogContent>
                        <AlertDialogHeader>
                          <AlertDialogTitle>Are you sure?</AlertDialogTitle>
                          <AlertDialogDescription>
                            This action cannot be undone. This will permanently delete the URL
                            <strong> {url.code}</strong> and remove it from the database.
                          </AlertDialogDescription>
                        </AlertDialogHeader>
                        <AlertDialogFooter>
                          <AlertDialogCancel>Cancel</AlertDialogCancel>
                          <AlertDialogAction
                            onClick={() => handleDelete(url.id)}
                            className="bg-red-600 hover:bg-red-700"
                          >
                            Delete
                          </AlertDialogAction>
                        </AlertDialogFooter>
                      </AlertDialogContent>
                    </AlertDialog>
                  </ButtonGroup>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      )}
    </div>
  );
}
