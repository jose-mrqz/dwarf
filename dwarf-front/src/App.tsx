import { Outlet } from "react-router-dom";
import "./App.css";
import { SiteHeader } from "@/components/site-header";

function App() {
  return (
    <>
      <main className="min-h-screen">
        <SiteHeader />
        <Outlet />
      </main>
    </>
  );
}

export default App;
